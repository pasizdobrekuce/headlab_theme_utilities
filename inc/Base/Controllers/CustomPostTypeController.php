<?php

/**
 * @package HeadlabThemeUtilities
 */

 namespace Inc\Base\Controllers;

 use Inc\Api\SettingsApi;
 use Inc\Base\BaseController;
 use Inc\Api\Callbacks\CptCallbacks;
 use Inc\Api\Callbacks\AdminCallbacks;

 class CustomPostTypeController extends BaseController
 {
    // Settings
    public $settings;
    
    // Subpages
    public $subpages = [];

    // Callbacks
    public $callbacks;

    // Callbacks
    public $cpt_callbacks;

    // CPTs
    public $custom_post_types = [];

    public function register()
    {
        // Stop execution if option not activated
        if(!$this->activated('cpt_manager')) return;

        // Init Settings API interface
        $this->settings = new SettingsApi();
        
        // Callbacks
        $this->callbacks = new AdminCallbacks();
        
        // Cpt callbacks
        $this->cpt_callbacks = new CptCallbacks();

        // Set plugin subpages
        $this->setSubPages();

        // Set settings
        $this->setSettings();

        // Set sections
        $this->setSections();

        // Set fields
        $this->setFields();
        
        // Execute Settings API interface methods
        $this->settings->addSubPages($this->subpages)->register();

        // Store custom post types
        $this->storeCustomPostTypes();

        if(!empty($this->custom_post_types)) {
            add_action( 'init', [$this, 'registerCustomPostTypes'] );
        }
    }
    
    /**
     * Set plugin CPT subpage
     *
     * @return void
     */
    public function setSubPages()
    {
        // Subpages
        $this->subpages = [
            [
                'parent_slug'   => 'headlab_theme_utilities',
                'page_title'    => __('Custom post types', 'headlab-theme-utilities'),
                'menu_title'    => __('CPT', 'headlab-theme-utilities'),
                'capability'    => 'manage_options',
                'menu_slug'     => 'headlab_theme_utilities_cpt',
                'callback'      => [$this->callbacks, 'adminCpt']
            ]
        ];
    }
    
    public function setSettings()
    {  

        $args = [
            [
                'option_group'  => 'headlab_theme_utilities_cpt_settings',
                'option_name'   => 'headlab_theme_utilities_cpt',
                'callback'      => [$this->cpt_callbacks, 'cptSanitize']
            ]
        ];

        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = [
            [
                'id'            => 'headlab_theme_utilities_cpt',
                'title'         => 'Create/Edit Custom Post Types',
                'callback'      => [$this->cpt_callbacks, 'headlabThemeUtilitiesCptManager'],
                'page'          => 'headlab_theme_utilities_cpt'
            ]
        ];

        $this->settings->setSections($args);
    }

    public function setFields()
    {
        /**
         * @src inc\Base\BaseController.php
         */
        $args = [
            [
                'id'            => 'post_type',
                'title'         => 'Custom Post Type ID',
                'callback'      => [$this->cpt_callbacks, 'textField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'post_type',
                    'placeholder' => 'e.g. book',
                    'description' => isset($_POST['edit_post_type']) ? 'You can not edit this.' : 'You can not edit this later.',
                    'required' => true,
                    'disabled' => true
                ]
            ],
            [
                'id'            => 'singular_name',
                'title'         => 'Singular Name',
                'callback'      => [$this->cpt_callbacks, 'textField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'singular_name',
                    'placeholder' => 'e.g. Book',
                    'required' => true,
                ]
            ],
            [
                'id'            => 'name',
                'title'         => 'Name',
                'callback'      => [$this->cpt_callbacks, 'textField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'name',
                    'placeholder' => 'e.g. Books',
                    'required' => true,
                ]
            ],
            [
                'id'            => 'menu_icon',
                'title'         => 'Menu Icon',
                'callback'      => [$this->cpt_callbacks, 'textField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'menu_icon',
                    'placeholder' => 'e.g. dashicons-beer',
                    'description' => 'See full list of icons <a href="'. esc_url( 'https://developer.wordpress.org/resource/dashicons/' ) .'" target="_blank">here</a>'
                ]
            ],
            [
                'id'            => 'hierarchical',
                'title'         => 'Hierarchical',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'hierarchical',
                    'class' => 'ui-toggle',
                    'description' => 'Does CPT follow hierarchy?',
                ]
            ],
            [
                'id'            => 'public',
                'title'         => 'Public',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'public',
                    'class' => 'ui-toggle',
                    'description' => 'Is CPT publicly accessible?',
                ]
            ],
            [
                'id'            => 'show_ui',
                'title'         => 'Show UI',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'show_ui',
                    'class' => 'ui-toggle',
                    'description' => 'Show CPT\'s admin UI. <strong>Show in menu</strong> must be checked.',
                ]
            ],
            [
                'id'            => 'show_in_menu',
                'title'         => 'Show in menu',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'show_in_menu',
                    'class' => 'ui-toggle',
                    'description' => 'Show CPT in menu.',
                ]
            ],
            [
                'id'            => 'show_in_admin_bar',
                'title'         => 'Show in admin bar',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'show_in_admin_bar',
                    'class' => 'ui-toggle',
                    'description' => 'Show CPT in admin bar.',
                ]
            ],
            [
                'id'            => 'show_in_nav_menus',
                'title'         => 'Show in nav menus',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'show_in_nav_menus',
                    'class' => 'ui-toggle',
                    'description' => 'Show CPT in nav menus.',
                ]
            ],
            [
                'id'            => 'can_export',
                'title'         => 'Can export',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'can_export',
                    'class' => 'ui-toggle',
                    'description' => 'Is CPT exportable?',
                ]
            ],
            [
                'id'            => 'has_archive',
                'title'         => 'Archive',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'has_archive',
                    'class' => 'ui-toggle',
                    'description' => 'Does CPT support archive pages?',
                ]
            ],
            [
                'id'            => 'exclude_from_search',
                'title'         => 'Exclude from search',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'exclude_from_search',
                    'class' => 'ui-toggle',
                    'description' => 'Is CPT excluded form search results?',
                ]
            ],
            [
                'id'            => 'publicly_queryable',
                'title'         => 'Publicly queryable',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'publicly_queryable',
                    'class' => 'ui-toggle',
                    'description' => 'Is CPT publicly queryable?',
                ]
            ],
            [
                'id'            => 'show_in_rest',
                'title'         => 'Show in REST Api',
                'callback'      => [$this->cpt_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_cpt',
                'section'       => 'headlab_theme_utilities_cpt',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_cpt',
                    'label_for' => 'show_in_rest',
                    'class' => 'ui-toggle',
                    'description' => 'Check this to use block editor.',
                ]
            ]
        ];

        $this->settings->setFields($args);
    }

    public function storeCustomPostTypes()
    {
        $options = get_option('headlab_theme_utilities_cpt') ?: [];

        foreach($options as $option) {
            
            $this->custom_post_types[] = [
                'post_type'             => $option['post_type'],
                'name'                  => $option['name'],
                'singular_name'         => $option['singular_name'],
                'menu_name'             => $option['name'],
                'name_admin_bar'        => $option['singular_name'],
                'archives'              => $option['singular_name'] . ' Archives',
                'attributes'            => $option['singular_name'] . ' Attributes',
                'parent_item_colon'     => 'Parent ' . $option['singular_name'],
                'all_items'             => 'All ' . $option['name'],
                'add_new_item'          => 'Add New ' . $option['singular_name'],
                'add_new'               => 'Add New',
                'new_item'              => 'New ' . $option['singular_name'],
                'edit_item'             => 'Edit ' . $option['singular_name'],
                'update_item'           => 'Update ' . $option['singular_name'],
                'view_item'             => 'View ' . $option['singular_name'],
                'view_items'            => 'View ' . $option['name'],
                'search_items'          => 'Search ' . $option['singular_name'],
                'not_found'             => 'No ' . $option['name'] . ' Found',
                'not_found_in_trash'    => 'No ' . $option['name'] . ' Found in Trash',
                'featured_image'        => 'Featured image',
                'set_featured_image'    => 'Set Featured Image',
                'remove_featured_image' => 'Remove Featured Image',
                'use_featured_image'    => 'Use Featured Image',
                'insert_into_item'      => 'Insert into ' . $option['singular_name'],
                'uploaded_to_this_item' => 'Uploaded to this ' . $option['singular_name'],
                'items_list'            => $option['name'] . ' List',
                'items_list_navigation' => $option['name'] . ' List Navigation',
                'filter_items_list'     => 'Filter ' . $option['name'] . ' List',
                'label'                 => $option['singular_name'],
                'description'           => $option['name'] . ' Custom Post Type',
                'supports'              => ['title', 'editor', 'thumbnail'],
                'taxonomies'            => [],
                'hierarchical'          => isset($option['hierarchical']) ?: false,
                'public'                => isset($option['public']) ?: false,
                'show_ui'               => isset($option['show_ui']) ?: false,
                'show_in_menu'          => isset($option['show_in_menu']) ?: false,
                'show_in_rest'          => isset($option['show_in_rest']) ?: false,
                'menu_position'         => '',
                'menu_icon'             => $option['menu_icon'],
                'show_in_admin_bar'     => isset($option['show_in_admin_bar']) ?: false,
                'show_in_nav_menus'     => isset($option['show_in_nav_menus']) ?: false,
                'can_export'            => isset($option['can_export']) ?: false,
                'has_archive'           => isset($option['has_archive']) ?: false,
                'exclude_from_search'   => isset($option['exclude_from_search']) ?: false,
                'publicly_queryable'    => isset($option['publicly_queryable']) ?: false,
                'capability_type'       => 'post',
            ];
        }
    }

    public function registerCustomPostTypes()
    {
        foreach($this->custom_post_types as $post_type) {

            register_post_type( $post_type['post_type'],
				[
					'labels' => [
						'name'                  => $post_type['name'],
						'singular_name'         => $post_type['singular_name'],
						'menu_name'             => $post_type['menu_name'],
						'name_admin_bar'        => $post_type['name_admin_bar'],
						'archives'              => $post_type['archives'],
						'attributes'            => $post_type['attributes'],
						'parent_item_colon'     => $post_type['parent_item_colon'],
						'all_items'             => $post_type['all_items'],
						'add_new_item'          => $post_type['add_new_item'],
						'add_new'               => $post_type['add_new'],
						'new_item'              => $post_type['new_item'],
						'edit_item'             => $post_type['edit_item'],
						'update_item'           => $post_type['update_item'],
						'view_item'             => $post_type['view_item'],
						'view_items'            => $post_type['view_items'],
						'search_items'          => $post_type['search_items'],
						'not_found'             => $post_type['not_found'],
						'not_found_in_trash'    => $post_type['not_found_in_trash'],
						'featured_image'        => $post_type['featured_image'],
						'set_featured_image'    => $post_type['set_featured_image'],
						'remove_featured_image' => $post_type['remove_featured_image'],
						'use_featured_image'    => $post_type['use_featured_image'],
						'insert_into_item'      => $post_type['insert_into_item'],
						'uploaded_to_this_item' => $post_type['uploaded_to_this_item'],
						'items_list'            => $post_type['items_list'],
						'items_list_navigation' => $post_type['items_list_navigation'],
						'filter_items_list'     => $post_type['filter_items_list']
                    ],
					'label'                     => $post_type['label'],
					'description'               => $post_type['description'],
					'supports'                  => $post_type['supports'],
					'taxonomies'                => $post_type['taxonomies'],
					'hierarchical'              => $post_type['hierarchical'],
					'public'                    => $post_type['public'],
					'show_ui'                   => $post_type['show_ui'],
					'show_in_menu'              => $post_type['show_in_menu'],
					'show_in_rest'              => $post_type['show_in_rest'],
					'menu_position'             => $post_type['menu_position'] ? $post_type['menu_position'] : '',
                    'menu_icon'                 => $post_type['menu_icon'] ? $post_type['menu_icon'] : 'dashicons-beer',
					'show_in_admin_bar'         => $post_type['show_in_admin_bar'],
					'show_in_nav_menus'         => $post_type['show_in_nav_menus'],
					'can_export'                => $post_type['can_export'],
					'has_archive'               => $post_type['has_archive'],
					'exclude_from_search'       => $post_type['exclude_from_search'],
					'publicly_queryable'        => $post_type['publicly_queryable'],
					'capability_type'           => $post_type['capability_type'],
                ]
			);
        }
    }
 }