<?php

declare(strict_types=1);

namespace App\Post;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $dependencyInjection): void {
    $services = $dependencyInjection
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services
        ->load('App\\Post\\', '../../src/Post/*')
        ->exclude('../../src/Post/{DependencyInjection,Entity,Tests,Kernel.php,di.php}')
    ;
};
