<?php
/**
 * @package HeadlabThemeUtilities
 */

 namespace Inc\Base;

 use Inc\Base\BaseController;

class SettingsLinks extends BaseController
{
    public function register()
    {
        // Add action links
        add_filter( "plugin_action_links_$this->plugin_name", [$this, 'addActionLinks'], 10, 5);
        // Add meta links
        add_filter( 'plugin_row_meta', [$this, 'addMetaLinks'], 10, 2 );
    }


    /**
     * Add action links
     *
     * @param [array] $links
     * @return array
     */
    public function addActionLinks($links): array
    {
        $action_links = [
            '<a href="' . admin_url( 'admin.php?page=headlab_theme_utilities' ) . '">'. __('Settings', 'headlab-theme-utilities') .'</a>',
        ];

        return array_merge( $links, $action_links );
    }


    /**
     * Add meta links
     *
     * @param [array] $links
     * @param [string] $file
     * @return array
     */
    public function addMetaLinks( $links, $file ): array
    {

        if ( strpos( $file, 'headlab-theme-utilities.php' ) !== false ) {
            $meta_links = [
                '<a href="donation_url" target="_blank"><strong>Donate</strong></a>'
            ];

            $links = array_merge( $links, $meta_links );
        }
        
        return $links;
    }
}