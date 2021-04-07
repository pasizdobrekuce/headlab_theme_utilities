<div class="wrap">
    <h1 class="mb-2">Taxonomy manager</h1>
    
    <?php settings_errors(); ?>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link <?php echo isset($_POST['edit_taxonomy']) ? '' : 'active'; ?>" data-toggle="tab" href="#tab-1">
                <?php _e('Taxonomies', 'headlab-theme-utilities'); ?>
            </a>
            <a class="nav-item nav-link <?php echo isset($_POST['edit_taxonomy']) ? 'active' : ''; ?>" data-toggle="tab" href="#tab-2">
                <?php 
                 if(isset($_POST['edit_taxonomy'])) {
                    _e('Edit taxonomy', 'headlab-theme-utilities'); 
                 } else {
                    _e('Add taxonomy', 'headlab-theme-utilities'); 
                 }
                ?>
            </a>
            <a class="nav-item nav-link " id="nav-export-tab" data-toggle="tab" href="#tab-3">
                <?php _e('Export', 'headlab-theme-utilities'); ?>
            </a>
        </div>
    </nav>

    <div class="tab-content shadow" id="nav-tabContent">
        <div class="tab-pane p-4 show <?php echo isset($_POST['edit_taxonomy']) ? '' : 'active'; ?>" id="tab-1">
            <h2>Manage taxonomies</h2>

            <?php
            if( ! get_option( 'headlab_theme_utilities_taxonomy' )) { 
                echo "<p>You have not added any taxonomies yet.</p>";
                $options = [];
            } else {
                $options = get_option('headlab_theme_utilities_taxonomy'); 
            }
            ?>

            <table class="table table-bordered border-0 mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Singular name</th>
                        <th scope="col">Taxonomy ID</th>
                        <th scope="col" class="text-center">Hierarchical</th>
                        <th scope="col" class="text-center">Public</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 1;
                     foreach($options as $option) : 
                        $hierarchical = isset($option['hierarchical']) ? '<b style="color: green;">Yes</b>' : '<b style="color: red;">No</b>';
                        $public = isset($option['public']) ? '<b style="color: green;">Yes</b>' : '<b style="color: red;">No</b>';
                     ?>
                        <tr>
                            <th scope="row"><?php echo $count; ?></th>
                            <td><a href="<?php echo admin_url( 'edit-tags.php?taxonomy=' . $option['taxonomy'] ); ?>"><?php echo $option['name']; ?></a></td>
                            <td><?php echo $option['singular_name']; ?></td>
                            <td><?php echo $option['taxonomy']; ?></td>
                            <td class="text-center"><?php echo $hierarchical; ?></td>
                            <td class="text-center"><?php echo $public; ?></td>
                            <td class="text-center">

                                <form action="" method="post" class="d-inline-block">
                                <?php
                                echo '<input type="hidden" name="edit_taxonomy" value="'. $option['taxonomy'] .'" />';
                                submit_button( 'Edit', 'primary small', 'submit', false);
                                ?>
                                </form>

                                <form action="options.php" method="post" class="d-inline-block">
                                <?php 
                                settings_fields('headlab_theme_utilities_taxonomy_settings');
                                echo '<input type="hidden" name="remove" value="'. $option['taxonomy'] .'" />';
                                submit_button( 'Delete', 'delete small', 'submit', false, [
                                    'onclick' => 'return confirm("Are you sure you want to delete this taxonomy? The data associated with it will be deleted.");'
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

        <div class="tab-pane p-4  <?php echo isset($_POST['edit_taxonomy']) ? 'active' : ''; ?>" id="tab-2">
            <form action="options.php" method="post">
                <?php 
                    settings_fields('headlab_theme_utilities_taxonomy_settings');
                    do_settings_sections( 'headlab_theme_utilities_taxonomy' );
                    echo "<hr class='mb-0'>";
                    submit_button();
                ?>
            </form>
        </div>

        <div class="tab-pane p-4" id="tab-3">
            <h2>Export</h2>
            <?php if($options) :?>
            <p>Copy and paste this code in your theme's "functions.php" file.</p>
            <hr>
            <?php else : ?>
            <p class="mb-0">Nothing to export yet.</p>
            <?php endif; ?>
            
<?php foreach($options as $option) : ?>

<p><strong><?php echo $option['singular_name']; ?></strong></p>

<pre class="prettyprint p-5 rounded shadow-lg">
// Register <?php echo $option['singular_name']; ?> Taxonomy
function <?php echo $option['taxonomy']; ?>_taxonomy() {

	$labels = [
		'name'                       => _x( '<?php echo $option['name']; ?>', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( '<?php echo $option['singular_name']; ?>', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( '<?php echo $option['singular_name']; ?>', 'text_domain' ),
		'all_items'                  => __( 'All <?php echo $option['name']; ?>', 'text_domain' ),
		'parent_item'                => __( 'Parent <?php echo $option['singular_name']; ?>', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent <?php echo $option['singular_name']; ?>:', 'text_domain' ),
		'new_item_name'              => __( 'New <?php echo $option['singular_name']; ?> Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New <?php echo $option['singular_name']; ?>', 'text_domain' ),
		'edit_item'                  => __( 'Edit <?php echo $option['singular_name']; ?>', 'text_domain' ),
		'update_item'                => __( 'Update <?php echo $option['singular_name']; ?>', 'text_domain' ),
		'view_item'                  => __( 'View <?php echo $option['singular_name']; ?>', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular <?php echo $option['name']; ?>', 'text_domain' ),
		'search_items'               => __( 'Search <?php echo $option['name']; ?>', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( '<?php echo $option['name']; ?> list', 'text_domain' ),
		'items_list_navigation'      => __( '<?php echo $option['name']; ?> list navigation', 'text_domain' ),
	];

	$args = [
		'labels'                     => $labels,
		'hierarchical'               => <?php echo isset($option['hierarchical']) ? 'true' : 'false'; ?>,
		'public'                     => <?php echo isset($option['public']) ? 'true' : 'false'; ?>,
		'show_ui'                    => <?php echo isset($option['show_ui']) ? 'true' : 'false'; ?>,
		'show_admin_column'          => <?php echo isset($option['show_admin_column']) ? 'true' : 'false'; ?>,
		'show_in_menu'               => <?php echo isset($option['show_in_menu']) ? 'true' : 'false'; ?>,
		'show_in_nav_menus'          => <?php echo isset($option['show_in_nav_menus']) ? 'true' : 'false'; ?>,
		'show_tagcloud'              => <?php echo isset($option['show_tagcloud']) ? 'true' : 'false'; ?>,
		'show_in_rest'               => <?php echo isset($option['show_in_rest']) ? 'true' : 'false'; ?>,
		'rewrite'                    => ['slug' => '<?php echo $option['taxonomy']; ?>'],
	];

	// TODO: Assign taxonomy to desired post types
	register_taxonomy( '<?php echo $option['taxonomy']; ?>', [ 'post' ], $args );

}
add_action( 'init', '<?php echo $option['taxonomy']; ?>_taxonomy', 0 );
</pre>

<?php endforeach; ?>
        </div>
        
    </div><!-- /.tab-content -->
</div>

