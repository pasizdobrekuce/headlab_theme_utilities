<?php

/**
 * On plugin uninstall
 * 
 * @package HeadlabThemeUtilities
 */

defined('WP_UNINSTALL_PLUGIN') or die('No');

$options = [
    'headlab_theme_utilities',
    'headlab_theme_utilities_cpt',
    'headlab_theme_utilities_taxonomy'
];

foreach($options as $option) {
    delete_option($option);
}