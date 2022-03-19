<?php

declare(strict_types=1);

namespace App\MyBusinessFeature;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $dependencyInjection): void {
    $services = $dependencyInjection
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services
        ->load('App\\Auth\\', '../../src/Auth/*')
        ->exclude('../../src/Auth/{DependencyInjection,Entity,Tests,Kernel.php,di.php,routing.php}')
    ;

    $services
        ->load('App\\Auth\\Controller\\', '../../src/Auth/Controller')
        ->tag('controller.service_arguments')
    ;

    $services->set('user.some_service', \stdClass::class); // TODO: replace with actual service
};
