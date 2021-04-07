<?php
/**
 * @package HeadlabThemeUtilities
 */

 namespace Inc\Pages;

 use Inc\Api\SettingsApi;
 use Inc\Base\BaseController;
 use Inc\Api\Callbacks\AdminCallbacks;
 use Inc\Api\Callbacks\ManagerCallbacks;

 class Dashboard extends BaseController
 {

    // Settings API
    public $settings;
    // Callbacks
    public $callbacks;
    public $callbacks_manager;
    // Pages
    public $pages = [];
    // Subpages
    //public $subpages = [];

    // Run
    public function register()
    {
        // Init Settings API interface
        $this->settings = new SettingsApi();
        // Callbacks
        $this->callbacks = new AdminCallbacks();
        $this->callbacks_manager = new ManagerCallbacks();
        // Set plugin main pages
        $this->setPages();
        // Set plugin subpages
        //$this->setSubPages();
        // Set settings
        $this->setSettings();
        // Set sections
        $this->setSections();
        // Set fields
        $this->setFields();
        
        // Execute Settings API interface methods
        $this->settings->addPages($this->pages)->withSubPage('Dashboard')->register();
    }

    /**
     * Set plugin main pages
     *
     * @return void
     */
    public function setPages()
    {
        // Main pages
        $this->pages = [
            [
                'page_title'    => __('Theme utilities', 'headlab-theme-utilities'),
                'menu_title'    => __('Theme utilities', 'headlab-theme-utilities'),
                'capability'    => 'manage_options',
                'menu_slug'     => 'headlab_theme_utilities',
                'callback'      => [$this->callbacks, 'adminDashboard'],
                'icon_url'      => 'dashicons-beer',
                'position'      => null
            ]
        ];
    }

    public function setSettings()
    {  

        $args = [
            [
                'option_group'  => 'headlab_theme_utilities_settings',
                'option_name'   => 'headlab_theme_utilities',
                'callback'      => [$this->callbacks_manager, 'checkboxSanitize']
            ]
        ];

        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = [
            [
                'id'            => 'headlab_theme_utilities_general',
                'title'         => 'Settings Manager',
                'callback'      => [$this->callbacks_manager, 'headlabThemeUtilitiesGeneralManager'],
                'page'          => 'headlab_theme_utilities'
            ]
        ];

        $this->settings->setSections($args);
    }

    public function setFields()
    {
        $args = [];

        /**
         * @src inc\Base\BaseController.php
         */
        foreach($this->managers as $key => $value) {
            $args[] = [
                'id'            => $key,
                'title'         => $value,
                'callback'      => [$this->callbacks_manager, 'checkboxField'],
                'page'          => 'headlab_theme_utilities',
                'section'       => 'headlab_theme_utilities_general',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities',
                    'label_for' => $key,
                    'class'     => 'ui-toggle'
                ]
            ];
        }

        $this->settings->setFields($args);
    }
 }
 