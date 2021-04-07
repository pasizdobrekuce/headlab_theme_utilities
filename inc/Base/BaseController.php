<?php
/**
 * @package HeadlabThemeUtilities
 */

namespace Inc\Base;

class BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin_name;

    public $managers = [];

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__DIR__), 2);
        $this->plugin_name = basename( plugin_dir_path(  dirname( __FILE__ , 2 ) ) ) . '/headlab-theme-utilities.php'; // OMG

        $this->managers = [
            'cpt_manager'       => __('Activate CPT manger', 'headlab-theme-utilities'),
            'taxonomy_manager'  => __('Activate Taxonomy manager', 'headlab-theme-utilities'),
        ];
    }
    
    /**
     * 
     * Checks if is general settings option checked
     * 
     * @source inc\Base\BaseController.php 
     * @param [string] $name
     * @return boolean
     */
    public function activated($name)
    {
        $option = get_option( 'headlab_theme_utilities' );
        return $option && $option[$name];
    }
}