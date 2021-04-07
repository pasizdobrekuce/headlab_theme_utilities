<?php

/**
 * @package HeadlabThemeUtilities
 */

namespace Inc\Base\Controllers;

 use Inc\Api\SettingsApi;
 use Inc\Base\BaseController;
 use Inc\Api\Callbacks\AdminCallbacks;
 use Inc\Api\Callbacks\TaxonomyCallbacks;

 class TaxonomyController extends BaseController
 {
    // Settings
    public $settings;

    // Callbacks
    public $callbacks;

    // Taxonomy callbacks
    public $tax_callbacks;
    
    // Subpages
    public $subpages = [];

    // Taxonomies
    public $taxonomies = [];

    public function register()
    {
        // Stop execution if option not activated
        if(!$this->activated('taxonomy_manager')) return;

        // Init Settings API interface
        $this->settings = new SettingsApi();
        
        // Callbacks
        $this->callbacks = new AdminCallbacks();

        // Taxonomy callbacks
        $this->tax_callbacks = new TaxonomyCallbacks();

        // Set taxonomy subpage
        $this->setSubPages();

        // Set taxonomy settings
        $this->setSettings();

        // Set sections
        $this->setSections();

        // Set fields
        $this->setFields();
        
        // Execute Settings API interface methods
        $this->settings->addSubPages($this->subpages)->register();

        // Store taxonomies
        $this->storeTaxonomies();

        if(!empty($this->taxonomies)) {
            add_action('init', [$this, 'registerTaxonomies']);
        }
    }
    
    /**
     * Set taxonomy subpage
     *
     * @return void
     */
    public function setSubPages()
    {
        // Subpages
        $this->subpages = [
            [
                'parent_slug'   => 'headlab_theme_utilities',
                'page_title'    => __('Taxonomy manager', 'headlab-theme-utilities'),
                'menu_title'    => __('Taxonomies', 'headlab-theme-utilities'),
                'capability'    => 'manage_options',
                'menu_slug'     => 'headlab_theme_utilities_taxonomy',
                'callback'      => [$this->callbacks, 'adminTaxonomy']
            ],
        ];
    }

    public function setSettings()
    {
        $args = [
            [
                'option_group'  => 'headlab_theme_utilities_taxonomy_settings',
                'option_name'   => 'headlab_theme_utilities_taxonomy',
                'callback'      => [$this->tax_callbacks, 'taxonomySanitize']
            ]
        ];

        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = [
            [
                'id'            => 'headlab_theme_utilities_taxonomy',
                'title'         => 'Create/Edit Taxonomies',
                'callback'      => [$this->tax_callbacks, 'headlabThemeUtilitiesTaxonomyManager'],
                'page'          => 'headlab_theme_utilities_taxonomy'
            ]
        ];

        $this->settings->setSections($args);
    }

    public function setFields()
    {
        $args = [
            [
                'id'        => 'taxonomy',
                'title'     => 'Taxonomy ID',
                'callback'  => [$this->tax_callbacks, 'textField'],
                'page'      => 'headlab_theme_utilities_taxonomy',
                'section'   => 'headlab_theme_utilities_taxonomy',
                'args'      => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'taxonomy',
                    'placeholder' => 'e.g. genre',
                    'description' => 'You can not change this.',
                    'required' => true,
                    'disabled' => true
                ]
            ],
            [
                'id'            => 'singular_name',
                'title'         => 'Singular Name',
                'callback'      => [$this->tax_callbacks, 'textField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'singular_name',
                    'placeholder' => 'e.g. Genre',
                    'required' => true,
                ]
            ],
            [
                'id'            => 'name',
                'title'         => 'Name',
                'callback'      => [$this->tax_callbacks, 'textField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'name',
                    'placeholder' => 'e.g. Genres',
                    'required' => true,
                ]
            ],
            [
                'id'            => 'hierarchical',
                'title'         => 'Hierarchical',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'hierarchical',
                    'class' => 'ui-toggle',
                    'description' => 'Is Taxonomy hierarchical?',
                ]
            ],
            [
                'id'            => 'public',
                'title'         => 'Public',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'public',
                    'class' => 'ui-toggle',
                    'description' => 'Is Taxonomy publicly accesible?',
                ]
            ],
            [
                'id'            => 'show_ui',
                'title'         => 'Show UI',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'show_ui',
                    'class' => 'ui-toggle',
                    'description' => 'Show taxonomy admin UI?',
                ]
            ],
            [
                'id'            => 'show_admin_column',
                'title'         => 'Show admin column',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'show_admin_column',
                    'class' => 'ui-toggle',
                    'description' => 'Show taxonomy admin column.',
                ]
            ],
            [
                'id'            => 'show_in_menu',
                'title'         => 'Show in menu',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'show_in_menu',
                    'class' => 'ui-toggle',
                    'description' => 'Show taxonomy in admin menu.',
                ]
            ],
            [
                'id'            => 'show_in_nav_menus',
                'title'         => 'Show in nav menus',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'show_in_nav_menus',
                    'class' => 'ui-toggle',
                    'description' => 'Show taxonomy in nav menus.',
                ]
            ],
            [
                'id'            => 'show_tagcloud',
                'title'         => 'Show tag cloud',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'show_tagcloud',
                    'class' => 'ui-toggle',
                    'description' => 'Show taxonomy\'s tag cloud.',
                ]
            ],
            [
                'id'            => 'show_in_rest',
                'title'         => 'Show in REST',
                'callback'      => [$this->tax_callbacks, 'checkboxField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'show_in_rest',
                    'class' => 'ui-toggle',
                    'description' => 'Show in REST Api.',
                ]
            ],
            [
                'id'            => 'objects',
                'title'         => 'Post Types',
                'callback'      => [$this->tax_callbacks, 'checkboxObjectsField'],
                'page'          => 'headlab_theme_utilities_taxonomy',
                'section'       => 'headlab_theme_utilities_taxonomy',
                'args'          => [
                    'option_name' => 'headlab_theme_utilities_taxonomy',
                    'label_for' => 'objects',
                    'class' => 'ui-toggle',
                    'description' => 'Choose post types to assign this taxonomy to. There is no default.',
                ]
            ]
        ];

        $this->settings->setFields($args);
    }

    public function storeTaxonomies()
    {
        $options = get_option('headlab_theme_utilities_taxonomy') ?: [];

        foreach($options as $option) {

            $labels = [
                'name'              => $option['name'],
                'singular_name'     => $option['singular_name'],
                'search_items'      => 'Search ' . $option['singular_name'],
                'all_items'         => 'All ' . $option['name'],
                'parent_item'       => 'Parent ' . $option['singular_name'],
                'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
                'edit_item'         => 'Edit ' . $option['singular_name'],
                'update_item'       => 'Update ' . $option['singular_name'],
                'view_item'         => 'View ' . $option['singular_name'],
                'separate_items_with_commas' => 'Separate items with commas',
                'add_or_remove_items' => 'Add or remove items',
                'choose_from_most_used' => 'Choose from the most used',
                'popular_items'     => 'Popular ' . $option['name'],
                'search_items'      => 'Search ' . $option['name'],
                'add_new_item'      => 'Add New ' . $option['singular_name'],
                'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
                'menu_name'         => $option['name'],
                'items_list'        => $option['name'] . ' list',
                'items_list_navigation' => $option['name'] . ' list navigation',
                'not_found'         => 'Not found',
                'no_terms'          => 'No items',
            ];
         
            $this->taxonomies[] = [
                'labels'            => $labels,
                'hierarchical'      => isset($option['hierarchical']) ?: false,
                'public'            => isset($option['public']) ?: false,
                'show_ui'           => isset($option['show_ui']) ?: false,
                'show_admin_column' => isset($option['show_admin_column']) ?: false,
                'show_in_menu'      => isset($option['show_in_menu']) ?: false,
                'show_in_nav_menus' => isset($option['show_in_nav_menus']) ?: false,
                'show_tagcloud'     => isset($option['show_tagcloud']) ?: false,
                'show_in_rest'      => isset($option['show_in_rest']) ?: false,
                'rewrite'           => [ 'slug' => $option['taxonomy'] ],
                'objects'           => isset($option['objects']) ? $option['objects'] : null,
            ];
        }
    }

    public function registerTaxonomies()
    {
        foreach($this->taxonomies as $taxonomy) {

            $objects = isset($taxonomy['objects']) ? array_keys($taxonomy['objects']) : null;

            register_taxonomy( $taxonomy['rewrite']['slug'], $objects, $taxonomy );
        }
    }
 }