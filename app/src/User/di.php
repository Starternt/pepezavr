<?php

declare(strict_types=1);

namespace App\MyBusinessFeature;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $dependencyInjection): void {
    $services = $dependencyInjection
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->load('App\\User\\', '../../src/User/*')
        ->exclude('../../src/User/{DependencyInjection,Entity,Tests,Kernel.php,di.php,routing.php}');
};
