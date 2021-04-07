<?php
/**
 * @package HeadlabThemeUtilities
 */

 namespace Inc\Base;

 use Inc\Base\BaseController;

 class Enqueue extends BaseController
 {
    public function register()
    {
        // Enqueue admin scripts
        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue']);
        add_action( 'admin_footer', [$this, 'admin_enqueue_footer']);
    }

    /**
     * Enqueue admin scripts
     *
     * @return void
     */
    public function admin_enqueue() {
        wp_enqueue_script( 'headlab-theme-utilities-bs', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js' );
        wp_enqueue_style( 'headlab-theme-utilities', $this->plugin_url . 'dist/css/build.min.css' );
    }


    /**
     * Enqueue admin scripts in footer
     *
     * @return void
     */
    public function admin_enqueue_footer()
    {
        wp_enqueue_script( 'headlab-theme-utilities', $this->plugin_url . 'dist/js/build.min.js' );
    }
 }