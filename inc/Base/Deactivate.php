<?php
/**
 * @package HeadlabThemeUtilities
 */

 namespace Inc\Base;

class Deactivate
{
    public static function deactivate()
    {
        // Flush rules
        flush_rewrite_rules();
    }
}