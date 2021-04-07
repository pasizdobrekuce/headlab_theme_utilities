<?php
/**
 * @package HeadlabThemeUtilities
 */

namespace Inc\Api\Callbacks;

class CptCallbacks
{
    public function checkboxSanitize($input)
    {
        $output = [];
        
        foreach($this->managers as $key => $value) {
            $output[$key] = (isset($input[$key]) && $input[$key]) ? true : false;
        }

        return $output;
    }

    public function headlabThemeUtilitiesCptManager()
    {
        esc_html_e( 'Manage Custom Post Types', 'headlab-theme-utilities' );
    }

    public function cptSanitize($input)
    {
        // Strip all but underscore and smallcaps
        $input['post_type'] = preg_replace('/[^a-zA-Z_]/', '', strtolower($input['post_type']));
        
        // Is WooCommerce active
        $woocommerce_active = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ));

        // Get output
        $output = get_option('headlab_theme_utilities_cpt');

        // Unset array if WC active and post_type equals product
        if($input['post_type'] === 'product' && $woocommerce_active) {

            add_settings_error(
                'headlab_cpt_woocommerce_product_warning',
                esc_attr( 'settings_updated' ),
                __('You can not add "product" Custom Post Type if WooCommerce plugin is active.', 'headlab-theme-utilities'),
                'warning'
            );

            unset($output[$input['post_type']]);
            return $output;
        }

        // Delete all data on CPT delete
        if(isset($_POST['remove'])) {
            // Delete all of the data related to post type
            //$this->delete_cpt_related_data($_POST['remove']);

            // Unset post type from option array
            unset($output[$_POST['remove']]);

            return $output;
        }
        
        // If $output empty assign $input
        if ( count($output) == 0 ) {
			$output[$input['post_type']] = $input;
			return $output;
		}

        // Do $output
		foreach ($output as $key => $value) {
			if ($input['post_type'] === $key) {
				$output[$key] = $input;
			} else {
				$output[$input['post_type']] = $input;
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
        $disabled = isset($args['disabled']) && isset($_POST['edit_post_type']) ? 'disabled="disabled"' : false;
        $option_name = $args['option_name'];
        $value = '';

        if(isset($_POST['edit_post_type'])) {
            $input = get_option( $option_name );
            $value = $input[$_POST['edit_post_type']][$name];
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

        if(isset($_POST['edit_post_type'])) {
            $checkbox = get_option( $option_name );
            $checked = isset($checkbox[$_POST['edit_post_type']][$name]) ? true : false;
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

    public function delete_cpt_related_data($post_type)
    {
        global $wpdb;
        $wpdb->query("DELETE FROM wp_posts WHERE post_type = '$post_type'");
        $wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
        $wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");
    }
}