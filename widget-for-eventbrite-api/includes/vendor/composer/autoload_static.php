<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit72aac7b444feed73f69908750fb12dfb
{
    public static $files = array (
        'ce89ac35a6c330c55f4710717db9ff78' => __DIR__ . '/..' . '/kriswallsmith/assetic/src/functions.php',
        '8d50dc88e56bace65e1e72f6017983ed' => __DIR__ . '/..' . '/freemius/wordpress-sdk/start.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Process\\' => 26,
        ),
        'O' => 
        array (
            'OomphInc\\ComposerInstallersExtender\\' => 36,
        ),
        'F' => 
        array (
            'Fullworks_WP_Autoloader\\' => 24,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'OomphInc\\ComposerInstallersExtender\\' => 
        array (
            0 => __DIR__ . '/..' . '/oomphinc/composer-installers-extender/src',
        ),
        'Fullworks_WP_Autoloader\\' => 
        array (
            0 => __DIR__ . '/..' . '/alanef/wp_autoloader/src',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'ComponentInstaller' => 
            array (
                0 => __DIR__ . '/..' . '/robloach/component-installer/src',
            ),
        ),
        'A' => 
        array (
            'Assetic' => 
            array (
                0 => __DIR__ . '/..' . '/kriswallsmith/assetic/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit72aac7b444feed73f69908750fb12dfb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit72aac7b444feed73f69908750fb12dfb::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit72aac7b444feed73f69908750fb12dfb::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit72aac7b444feed73f69908750fb12dfb::$classMap;

        }, null, ClassLoader::class);
    }
}
