<?php

namespace TenWebOptimizer;

/**
 * @deprecated in favor of /TenWebWpTransients/OptimizerTransients class
 * A drop-in replacement class for WordPress Transients with changed option names in order to avoid their deletion
 */
class OptimizerTransients
{

    public const TRANSIENT_KEY = 'two_trans_';
    public const TRANSIENT_TIMEOUT_KEY = 'two_trans_timeout_';
    /**
     * Sets/updates the value of a TWO transient.
     *
     * You do not need to serialize values. If the value needs to be serialized,
     * then it will be serialized before it is set.
     *
     * @since 2.8.0
     * @see set_transient()
     * @param string $transient  Transient name. Expected to not be SQL-escaped.
     *                           Must be 172 characters or fewer in length.
     * @param mixed  $value      Transient value. Must be serializable if non-scalar.
     *                           Expected to not be SQL-escaped.
     * @param int    $expiration Optional. Time until expiration in seconds. Default 0 (no expiration).
     * @return bool True if the value was set, false otherwise.
     */
    public static function set($transient, $value, $expiration = 0) {

        $expiration = (int) $expiration;

        /**
         * Filters a specific TWO transient before its value is set.
         *
         * The dynamic portion of the hook name, `$transient`, refers to the transient name.
         *
         * @since 3.0.0
         * @since 4.2.0 The `$expiration` parameter was added.
         * @since 4.4.0 The `$transient` parameter was added.
         *
         * @param mixed  $value      New value of transient.
         * @param int    $expiration Time until expiration in seconds.
         * @param string $transient  Transient name.
         */
        $value = apply_filters( "two_pre_set_transient_{$transient}", $value, $expiration, $transient );

        /**
         * Filters the expiration for a TWO transient before its value is set.
         *
         * The dynamic portion of the hook name, `$transient`, refers to the transient name.
         *
         * @since 4.4.0
         *
         * @param int    $expiration Time until expiration in seconds. Use 0 for no expiration.
         * @param mixed  $value      New value of transient.
         * @param string $transient  Transient name.
         */
        $expiration = apply_filters( "two_expiration_of_transient_{$transient}", $expiration, $value, $transient );

        if ( wp_using_ext_object_cache() || wp_installing() ) {
            $result = wp_cache_set( self::TRANSIENT_KEY.'_'.$transient, $value, 'two_transient', $expiration ); // phpcs:ignore
        } else {
            $transient_timeout = self::TRANSIENT_TIMEOUT_KEY . $transient;
            $transient_option  = self::TRANSIENT_KEY . $transient;

            if ( false === get_option( $transient_option ) ) {
                $autoload = 'yes';
                if ( $expiration ) {
                    $autoload = 'no';
                    add_option( $transient_timeout, time() + $expiration, '', 'no' );
                }
                $result = add_option( $transient_option, $value, '', $autoload );
            } else {
                // If expiration is requested, but the transient has no timeout option,
                // delete, then re-create transient rather than update.
                $update = true;

                if ( $expiration ) {
                    if ( false === get_option( $transient_timeout ) ) {
                        delete_option( $transient_option );
                        add_option( $transient_timeout, time() + $expiration, '', 'no' );
                        $result = add_option( $transient_option, $value, '', 'no' );
                        $update = false;
                    } else {
                        update_option( $transient_timeout, time() + $expiration );
                    }
                }

                if ( $update ) {
                    $result = update_option( $transient_option, $value );
                }
            }
        }

        if ( $result ) {

            /**
             * Fires after the value for a specific transient has been set.
             *
             * The dynamic portion of the hook name, `$transient`, refers to the transient name.
             *
             * @since 3.0.0
             * @since 3.6.0 The `$value` and `$expiration` parameters were added.
             * @since 4.4.0 The `$transient` parameter was added.
             *
             * @param mixed  $value      Transient value.
             * @param int    $expiration Time until expiration in seconds.
             * @param string $transient  The name of the transient.
             */
            do_action( "two_set_transient_{$transient}", $value, $expiration, $transient );

            /**
             * Fires after the value for a transient has been set.
             *
             * @since 3.0.0
             * @since 3.6.0 The `$value` and `$expiration` parameters were added.
             *
             * @param string $transient  The name of the transient.
             * @param mixed  $value      Transient value.
             * @param int    $expiration Time until expiration in seconds.
             */
            do_action( 'two_setted_transient', $transient, $value, $expiration );
        }

        return $result;
    }


    /**
     * Retrieves the value of a transient.
     *
     * If the transient does not exist, does not have a value, or has expired,
     * then the return value will be false.
     *
     * @since 2.8.0
     * @see get_transient()
     * @param string $transient Transient name. Expected to not be SQL-escaped.
     * @return mixed Value of transient.
     */
    public static function get( $transient ) {

        /**
         * Filters the value of an existing transient before it is retrieved.
         *
         * The dynamic portion of the hook name, `$transient`, refers to the transient name.
         *
         * Returning a value other than false from the filter will short-circuit retrieval
         * and return that value instead.
         *
         * @since 2.8.0
         * @since 4.4.0 The `$transient` parameter was added
         *
         * @param mixed  $pre_transient The default value to return if the transient does not exist.
         *                              Any value other than false will short-circuit the retrieval
         *                              of the transient, and return that value.
         * @param string $transient     Transient name.
         */
        $pre = apply_filters( "two_pre_transient_{$transient}", false, $transient );

        if ( false !== $pre ) {
            return $pre;
        }

        if ( wp_using_ext_object_cache() || wp_installing() ) {
            $value = wp_cache_get( self::TRANSIENT_KEY.'_'.$transient, 'two_transient' );
        } else {
            $transient_option = self::TRANSIENT_KEY . $transient;
            if ( ! wp_installing() ) {
                // If option is not in alloptions, it is not autoloaded and thus has a timeout.
                $alloptions = wp_load_alloptions();
                if ( ! isset( $alloptions[ $transient_option ] ) ) {
                    $transient_timeout = self::TRANSIENT_TIMEOUT_KEY . $transient;
                    $timeout           = get_option( $transient_timeout );
                    if ( false !== $timeout && $timeout < time() ) {
                        delete_option( $transient_option );
                        delete_option( $transient_timeout );
                        $value = false; // phpcs:ignore
                    }
                }
            }

            if ( ! isset( $value ) ) {
                $value = get_option( $transient_option );
            }
        }

        /**
         * Filters an existing transient's value.
         *
         * The dynamic portion of the hook name, `$transient`, refers to the transient name.
         *
         * @since 2.8.0
         * @since 4.4.0 The `$transient` parameter was added
         *
         * @param mixed  $value     Value of transient.
         * @param string $transient Transient name.
         */
        return apply_filters( "two_transient_{$transient}", $value, $transient );
    }


    /**
     * Deletes a TWO transient.
     *
     * @since 2.8.0
     * @see delete_transient()
     * @param string $transient Transient name. Expected to not be SQL-escaped.
     * @return bool True if the transient was deleted, false otherwise.
     */
    public static function delete( $transient ) {

        /**
         * Fires immediately before a specific transient is deleted.
         *
         * The dynamic portion of the hook name, `$transient`, refers to the transient name.
         *
         * @since 3.0.0
         *
         * @param string $transient Transient name.
         */
        do_action( "two_delete_transient_{$transient}", $transient );

        if ( wp_using_ext_object_cache() || wp_installing() ) {
            $result = wp_cache_delete( self::TRANSIENT_KEY.'_'.$transient, 'two_transient' );
        } else {
            $option_timeout = self::TRANSIENT_TIMEOUT_KEY . $transient;
            $option         = self::TRANSIENT_KEY . $transient;
            $result         = delete_option( $option );

            if ( $result ) {
                delete_option( $option_timeout );
            }
        }

        if ( $result ) {

            /**
             * Fires after a transient is deleted.
             *
             * @since 3.0.0
             *
             * @param string $transient Deleted transient name.
             */
            do_action( 'two_deleted_transient', $transient );
        }

        return $result;
    }
}