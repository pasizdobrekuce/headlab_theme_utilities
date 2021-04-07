<?php
/**
 * @package HeadlabThemeUtilities
 */

namespace Inc\Api\Callbacks;

class TaxonomyCallbacks
{

    public function headlabThemeUtilitiesTaxonomyManager()
    {
        esc_html_e( 'Manage Taxonomies', 'headlab-theme-utilities' );
    }

    public function taxonomySanitize($input)
    {
        // Strip all but underscore and smallcaps
        $input['taxonomy'] = preg_replace('/[^a-zA-Z_]/', '', strtolower($input['taxonomy']));
        
        // Is WooCommerce active
        $woocommerce_active = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ));

        // Get output
        $output = get_option('headlab_theme_utilities_taxonomy');

        // Unset array if WC active and taxonomy equals product_cat or product_tag
        if(($input['taxonomy'] === 'product_cat' || $input['taxonomy'] === 'product_tag') && $woocommerce_active) {

            add_settings_error(
                'headlab_cpt_woocommerce_product_warning',
                esc_attr( 'settings_updated' ),
                'You can not add "'. $input['taxonomy'] .'" taxonomy if WooCommerce plugin is active.',
                'warning'
            );

            unset($output[$input['taxonomy']]);
            return $output;
        }

        // Unset array if WC active and taxonomy equals category or post_tag
        if($input['taxonomy'] === 'category' || $input['taxonomy'] === 'post_tag') {

            add_settings_error(
                'headlab_cpt_category_tag_warning',
                esc_attr( 'settings_updated' ),
                'You can not add "'. $input['taxonomy'] .'" taxonomy. It already exists, and it is assigned to "post" post type.',
                'warning'
            );

            unset($output[$input['taxonomy']]);
            return $output;
        }

        // Delete all data on taxonomy delete
        if(isset($_POST['remove'])) {
            // Delete all of the data related to taxonomy
            //$this->delete_taxonomy_related_data($_POST['remove']);

            // Unset post type from option array
            unset($output[$_POST['remove']]);

            return $output;
        }
        
        // If $output empty assign $input
        if ( count($output) == 0 ) {
			$output[$input['taxonomy']] = $input;
			return $output;
		}

        // Do $output
		foreach ($output as $key => $value) {
			if ($input['taxonomy'] === $key) {
				$output[$key] = $input;
			} else {
				$output[$input['taxonomy']] = $input;
			}
        }
		
		return $output;
    }

    public function textField($args)
    {
        // Get $args
        $name = $args['label_for'];
        $description = isset($args['description']) ? $args['description'] : false;
        $required = isset($args['required']) ? 'required="required"' : false;
        $disabled = isset($args['disabled']) && isset($_POST['edit_taxonomy']) ? 'disabled="disabled"' : false;
        $option_name = $args['option_name'];
        $value = '';

        if(isset($_POST['edit_taxonomy'])) {
            $input = get_option( $option_name );
            $value = $input[$_POST['edit_taxonomy']][$name];
        }

        echo'<input type="text" class="regular-text" id="'. $name .'" name="'. $option_name .'['. $name .']" value="'. $value .'" placeholder="'. $args['placeholder'] .'" '. $required .' '. $disabled .'/>';

        if($disabled) {
            echo'<input type="hidden" id="'. $name .'" name="'. $option_name .'['. $name .']" value="'. $value .'"/>';
        }

        if($description) {
            echo "<p class='description'>$description</p>";
        }
    }

    public function checkboxField($args)
    {
        // Get $args
        $name = $args['label_for'];
        $description = isset($args['description']) ? $args['description'] : false;
        $class = $args['class'];
        $option_name = $args['option_name'];
        $checked = false;

        if(isset($_POST['edit_taxonomy'])) {
            $checkbox = get_option( $option_name );
            $checked = isset($checkbox[$_POST['edit_taxonomy']][$name]) ? true : false;
        }

        // Output HTML
        echo "<label class='ui-toggle-switch'>";
        echo '<input type="checkbox" id="'. $name .'" name="'. $option_name .'['. $name .']" value="1" class="'. $class .' sr-only" '. ($checked ? 'checked' : '') .' />';
        echo "<span class='ui-toggle-slider round'></span>";
        echo "</label>";

        if($description) {
            echo "<label for='". $name ."' class='d-inline-block ml-2 mt-0 text-muted'>$description</label>";
        }
    }

    public function checkboxObjectsField($args)
    {

        // Declare output
        $output = '';

        // Get $args
        $name = $args['label_for'];
        $description = isset($args['description']) ? $args['description'] : false;
        $class = $args['class'];
        $option_name = $args['option_name'];
        $checked = false;

        // Set checked for editing taxonomy
        if(isset($_POST['edit_taxonomy'])) {
            $checkbox = get_option( $option_name );
        }

        // Get all post types
        $post_types = get_post_types([
            'show_ui'   => true,
            'public'    => true    
        ], 'object');

        // Unset unwanted post types
        unset($post_types['page']);

        if($description) {
            $output .= '<p class="description mb-3">'. $description .'</p>';
        }

        // Set $output
        foreach($post_types as $post_type) {

            // Get slug
            $slug = $post_type->name;

            // Get name
            $post_type_name = $post_type->labels->name;

            // Set checked for editing taxonomy
            if(isset($_POST['edit_taxonomy'])) {
                $checked = isset($checkbox[$_POST['edit_taxonomy']][$name][$slug]) ? true : false;
            }
            
            $output .= '<label class="ui-toggle-switch">';
            $output .= '<input type="checkbox" id="'. $slug .'" name="'. $option_name .'['. $name .']['. $slug .']" value="1" class="'. $class .' sr-only" '. ($checked ? 'checked' : '') .' />';
            $output .= '<span class="ui-toggle-slider round"></span>';
            $output .= '</label>';
            $output .= '<label for="'. $slug .'" class="d-inline-block ml-2 mt-0 text-muted">'. $post_type_name .'</label>';
            $output .= '<br><br>';
        }

        echo $output;
    }
}