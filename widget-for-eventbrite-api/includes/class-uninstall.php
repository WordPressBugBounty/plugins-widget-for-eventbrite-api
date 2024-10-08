<?php

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 */
namespace WidgetForEventbriteAPI\Includes;

class Uninstall {
    /**
     * Uninstall specific code
     */
    public static function uninstall() {
        global $wfea_fs;
        delete_option( 'widget-for-eventbrite-api-settings' );
        delete_option( 'wfea_transients' );
        delete_option( 'widget-for-eventbrite-api-version' );
    }

}
