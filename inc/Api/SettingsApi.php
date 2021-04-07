<?php
/**
 * @package HeadlabThemeUtilities
 */

namespace Inc\Api;

class SettingsApi 
{
    // Plugin main pages
    public $admin_pages = [];

    // Plugin subpages
    public $admin_subpages = [];

    // Settings
    public $settings = [];

    // Sections
    public $sections = [];

    // Fields
    public $fields = [];

    public function register()
    {
        if(!empty($this->admin_pages) || !empty($this->admin_subpages)) {
            add_action('admin_menu', [$this, 'addAdminMenu']);
        }

        if(!empty($this->settings)) {
            add_action('admin_init', [$this, 'registerCustomFields']);
        }
    }

    /**
     * Add plugin main pages
     *
     * @param array $pages
     * @return class instance
     */
    public function addPages(array $pages)
    {
        $this->admin_pages = $pages;
        return $this;
    }

    /**
     * Assign main page subpage
     *
     * @param string $title
     * @return class instance
     */
    public function withSubPage(string $title = null)
    {
        // Bail bail bail
        if(empty($this->admin_pages)) {
            return $this;
        }

        // Plugin page
        $admin_page = $this->admin_pages[0];

        // Plugin page subpage
        $subpage = [
            [
                'parent_slug'   => $admin_page['menu_slug'],
                'page_title'    => $admin_page['page_title'],
                'menu_title'    => $title ? $title : $admin_page['menu_title'],
                'capability'    => $admin_page['capability'],
                'menu_slug'     => $admin_page['menu_slug'],
                'callback'      => $admin_page['callback']
            ]
        ];

        $this->admin_subpages = $subpage;

        return $this;
    }

    /**
     * Add plugin subapages
     *
     * @param array $pages
     * @return class instance
     */
    public function addSubPages(array $pages)
    {
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);

        return $this;
    }

    /**
     * Add plugin pages
     *
     * @return void
     */
    public function addAdminMenu()
    {
        foreach($this->admin_pages as $page) {
            add_menu_page(
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback'],
                $page['icon_url'],
                $page['position']
            );
        }

        foreach($this->admin_subpages as $page) {
            add_submenu_page(
                $page['parent_slug'],
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback']
            );
        }
    }

    /**
     * Set setting groups
     *
     * @param array $settings
     * @return class instance
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Set sections
     *
     * @param array $sections
     * @return class instance
     */
    public function setSections(array $sections)
    {
        $this->sections = $sections;
        return $this;
    }

    /**
     * Set fields
     *
     * @param array $fields
     * @return class instance
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }


    public function registerCustomFields()
    {
        // Register setting groups
        foreach($this->settings as $setting) {
            register_setting( 
                $setting['option_group'], 
                $setting['option_name'], 
                (isset($setting['callback']) ? $setting['callback'] : '')
            );
        }

        // Add settings sections
        foreach($this->sections as $section) {
            add_settings_section( 
                $section['id'], 
                $section['title'], 
                (isset($section['callback']) ? $section['callback'] : ''), 
                $section['page'] 
            );
        }

        // Add settings fields
        foreach($this->fields as $field) {
            add_settings_field( 
                $field['id'], 
                $field['title'], 
                (isset($field['callback']) ? $field['callback'] : ''), 
                $field['page'], 
                $field['section'],
                (isset($field['args']) ? $field['args'] : '') 
            );
        }
    }
}