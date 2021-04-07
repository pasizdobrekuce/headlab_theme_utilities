<?php
/**
 * @package HeadlabThemeUtilities
 */

 namespace Inc\Base;

class Activate
{
    public static function activate()
    {
        // Flush rules
        flush_rewrite_rules();
        
        if( ! get_option( 'headlab_theme_utilities' )) {
            // Update option to default data
            update_option('headlab_theme_utilities', []);
        }
        
        if( ! get_option( 'headlab_theme_utilities_cpt' )) {
            // Update option to default data
            update_option('headlab_theme_utilities_cpt', []);
        }
        
        if( ! get_option( 'headlab_theme_utilities_taxonomy' )) {
            // Update option to default data
            update_option('headlab_theme_utilities_taxonomy', []);
        }

        
    }
}