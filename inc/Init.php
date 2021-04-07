<?php

/**
 * @package HeadlabThemeUtilities
 */

namespace Inc;
 
final class Init
{

    /**
     * Get all services/classes
     *
     * @return array
     */
    public static function get_services(): array
    {
        return [
            Pages\Dashboard::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\Controllers\CustomPostTypeController::class,
            Base\Controllers\TaxonomyController::class,
        ];
    }

    /**
     * Register services/classes
     *
     * @return void
     */
    public static function register_services()
    {
        foreach(self::get_services() as $class) {
            $service = self::instantiate($class);
            if(method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
     * Instantiate new class
     *
     * @param [class] $class
     * @return class
     */
    private static function instantiate($class)
    {
        $service = new $class();
        return $service;
    }
}