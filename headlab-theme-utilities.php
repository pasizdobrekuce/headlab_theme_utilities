<?php
/**
 * @package HeadlabThemeUtilities
 * 
 * Plugin Name: Headlab Theme Utilities
 * Plugin URI: https://headlab.io
 * Description: Utilities for Headlab themes.
 * Version: 1.0.0
 * Author: Headlab
 * Author URI: https://headlab.io
 * Licence: GPLv2 or later
 * Text Domain: headlab-theme-utilities
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('ABSPATH') or die('No');

/**
 * Require autolader
 */
if(file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * Code that runs during plugin activation
 *
 * @return void
 */
function activate_headlab_theme_utilities()
{
    Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_headlab_theme_utilities' );

/**
 * Code that runs during plugin deactivation
 *
 * @return void
 */
function deactivate_headlab_theme_utilities()
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_headlab_theme_utilities' );

/**
 * Initialize services
 */
if(class_exists('Inc\\Init')) {
    Inc\Init::register_services();
}

