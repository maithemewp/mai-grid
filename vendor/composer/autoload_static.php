<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit031020ac0959976b22d57590a58c9ab0
{
    public static $files = array (
        'eb560c198217526cfde8ceb63ae508de' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/load-v4p8.php',
    );

    public static $classMap = array (
        'Gamajo_Template_Loader' => __DIR__ . '/..' . '/gamajo/template-loader/class-gamajo-template-loader.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit031020ac0959976b22d57590a58c9ab0::$classMap;

        }, null, ClassLoader::class);
    }
}
