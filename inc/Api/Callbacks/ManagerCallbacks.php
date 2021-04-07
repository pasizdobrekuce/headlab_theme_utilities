<?php
/**
 * @package HeadlabThemeUtilities
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class ManagerCallbacks extends BaseController
{
    public function checkboxSanitize($input)
    {
        $output = [];
        
        foreach($this->managers as $key => $value) {
            $output[$key] = (isset($input[$key]) && $input[$key]) ? true : false;
        }

        return $output;
    }

    public function headlabThemeUtilitiesGeneralManager()
    {
        esc_html_e( 'Manage theme utilities', 'headlab-theme-utilities' );
    }

    public function checkboxField($args)
    {
        // Get $args
        $name = $args['label_for'];
        $class = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option( $option_name );
        $checked = $checkbox && $checkbox[$name];
        

        // Output HTML
        echo "<label class='ui-toggle-switch'>";
        echo '<input type="checkbox" id="'. $name .'" name="'. $option_name .'['. $name .']" value="1" class="'. $class .' sr-only"  '. ( $checked ? 'checked' : '') .' >';
        echo "<span class='ui-toggle-slider round'></span>";
        echo "</label>";
    }
}