<div class="wrap">
    <h1 class="mb-2">CPT manager</h1>
    
    <?php settings_errors(); ?>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link <?php echo isset($_POST['edit_post_type']) ? '' : 'active'; ?>" id="nav-general-tab" data-toggle="tab" href="#nav-post-types">
                <?php _e('Custom Post Types', 'headlab-theme-utilities'); ?>
            </a>
            <a class="nav-item nav-link <?php echo isset($_POST['edit_post_type']) ? 'active' : ''; ?>" id="nav-updates-tab" data-toggle="tab" href="#nav-add-post-types">
                <?php 
                 if(isset($_POST['edit_post_type'])) {
                    _e('Edit Custom Post Type', 'headlab-theme-utilities'); 
                 } else {
                    _e('Add Custom Post Type', 'headlab-theme-utilities'); 
                 }
                ?>
            </a>
            <a class="nav-item nav-link " id="nav-export-tab" data-toggle="tab" href="#nav-export-post-types">
                <?php _e('Export', 'headlab-theme-utilities'); ?>
            </a>
        </div>
    </nav>

    <div class="tab-content shadow" id="nav-tabContent">
        <div class="tab-pane p-4 show <?php echo isset($_POST['edit_post_type']) ? '' : 'active'; ?>" id="nav-post-types">
            <h2>Manage Custom Post Types</h2>

            <?php
            if( ! get_option( 'headlab_theme_utilities_cpt' )) { 
                echo "<p>You have not added any Custom Post Types yet.</p>";
                $options = [];
            } else {
                $options = get_option('headlab_theme_utilities_cpt'); 
            }
            ?>

            <table class="table table-bordered border-0 mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Singular name</th>
                        <th scope="col">Post type ID</th>
                        <th scope="col" class="text-center">Show UI</th>
                        <th scope="col" class="text-center">Show in menu</th>
                        <th scope="col" class="text-center">Public</th>
                        <th scope="col" class="text-center">Has archive</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 1;
                     foreach($options as $option) : 
                        $show_ui = isset($option['show_ui']) ? '<b style="color: green;">Yes</b>' : '<b style="color: red;">No</b>';
                        $show_in_menu = isset($option['show_in_menu']) ? '<b style="color: green;">Yes</b>' : '<b style="color: red;">No</b>';
                        $public = isset($option['public']) ? '<b style="color: green;">Yes</b>' : '<b style="color: red;">No</b>';
                        $has_archive = isset($option['has_archive']) ? '<b style="color: green;">Yes</b>' : '<b style="color: red;">No</b>';
                     ?>
                        <tr>
                            <th scope="row"><?php echo $count; ?></th>
                            <td><a href="<?php echo admin_url( 'edit.php?post_type=' . $option['post_type'] ); ?>"><?php echo $option['name']; ?></a></td>
                            <td><?php echo $option['singular_name']; ?></td>
                            <td><?php echo $option['post_type']; ?></td>
                            <td class="text-center"><?php echo $show_ui; ?></td>
                            <td class="text-center"><?php echo $show_in_menu; ?></td>
                            <td class="text-center"><?php echo $public; ?></td>
                            <td class="text-center"><?php echo $has_archive; ?></td>
                            <td class="text-center">

                                <form action="" method="post" class="d-inline-block">
                                <?php
                                echo '<input type="hidden" name="edit_post_type" value="'. $option['post_type'] .'" />';
                                submit_button( 'Edit', 'primary small', 'submit', false);
                                ?>
                                </form>

                                <form action="options.php" method="post" class="d-inline-block">
                                <?php 
                                settings_fields('headlab_theme_utilities_cpt_settings');
                                echo '<input type="hidden" name="remove" value="'. $option['post_type'] .'" />';
                                submit_button( 'Delete', 'delete small', 'submit', false, [
                                    'onclick' => 'return confirm("Are you sure you want to delete this Custom Post Type? The data associated with it will be deleted.");'
                                ]);
                                ?>
                                </form>
                            </td>
                        </tr>

                        
                    <?php   
                    $count++; 
                    endforeach; 
                    ?>
                </tbody>
            </table>
           
        </div>

        <div class="tab-pane p-4  <?php echo isset($_POST['edit_post_type']) ? 'active' : ''; ?>" id="nav-add-post-types">
            <form action="options.php" method="post">
                <?php 
                    settings_fields('headlab_theme_utilities_cpt_settings');
                    do_settings_sections( 'headlab_theme_utilities_cpt' );
                    echo "<hr class='mb-0'>";
                    submit_button();
                ?>
            </form>
        </div>

        <div class="tab-pane p-4" id="nav-export-post-types">
            <h2>Export</h2>
            <?php if($options) :?>
            <p>Copy and paste this code in your theme's "functions.php" file.</p>
            <hr>
            <?php else : ?>
            <p class="mb-0">Nothing to export yet.</p>
            <?php endif; ?>
            
<?php foreach($options as $option) : ?>

<p><strong><?php echo $option['singular_name']; ?></strong></p>

<pre class="prettyprint p-5 rounded shadow-lg">// Register <?php echo $option['singular_name']; ?> Custom Post Type
function <?php echo $option['post_type']; ?>_post_type() {

    $labels = [
        'name'                  => _x( '<?php echo $option['name']; ?>', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( '<?php echo $option['singular_name']; ?>', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( '<?php echo $option['name']; ?>', 'text_domain' ),
        'name_admin_bar'        => __( '<?php echo $option['singular_name']; ?>', 'text_domain' ),
        'archives'              => __( '<?php echo $option['singular_name']; ?> Archives', 'text_domain' ),
        'attributes'            => __( '<?php echo $option['singular_name']; ?> Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent <?php echo $option['singular_name']; ?>:', 'text_domain' ),
        'all_items'             => __( 'All <?php echo $option['name']; ?>', 'text_domain' ),
        'add_new_item'          => __( 'Add New <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'edit_item'             => __( 'Edit <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'update_item'           => __( 'Update <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'view_item'             => __( 'View <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'view_items'            => __( 'View <?php echo $option['name']; ?>', 'text_domain' ),
        'search_items'          => __( 'Search <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this <?php echo $option['singular_name']; ?>', 'text_domain' ),
        'items_list'            => __( '<?php echo $option['name']; ?> list', 'text_domain' ),
        'items_list_navigation' => __( '<?php echo $option['name']; ?> list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter <?php echo $option['singular_name']; ?> list', 'text_domain' ),
    ];

    $args = [
        'label'                 => __( '<?php echo $option['singular_name']; ?>', 'text_domain' ),
        'description'           => __( '<?php echo $option['singular_name']; ?> Description', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => [ 'title', 'editor', 'thumbnail' ],
        'taxonomies'            => false,
        'hierarchical'          => false,
        'public'                => <?php echo isset($option['public']) ? 'true' : 'false'; ?>,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => '',
        'menu_icon'             => '<?php echo $option['menu_icon']; ?>',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => <?php echo isset($option['has_archive']) ? 'true' : 'false'; ?>,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => <?php echo isset($option['show_in_rest']) ? 'true' : 'false'; ?>,
    ];

    register_post_type( '<?php echo $option['post_type']; ?>', $args );
    
}
add_action( 'init', '<?php echo $option['post_type']; ?>_post_type', 0 );</pre>

<?php endforeach; ?>

        </div>
        
    </div><!-- /.tab-content -->
</div>