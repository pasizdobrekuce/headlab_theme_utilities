<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit95be4f11471c4a26e98d8336a419d536
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Inc\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Inc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit95be4f11471c4a26e98d8336a419d536::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit95be4f11471c4a26e98d8336a419d536::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit95be4f11471c4a26e98d8336a419d536::$classMap;

        }, null, ClassLoader::class);
    }
}
