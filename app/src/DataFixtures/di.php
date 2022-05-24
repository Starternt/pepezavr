<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $dependencyInjection): void {
    $services = $dependencyInjection
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->load('App\\DataFixtures\\', '../../src/DataFixtures/*')
        ->tag('doctrine.fixture.orm')
        ->exclude('../../src/DataFixtures/{DependencyInjection,Entity,Tests,Kernel.php,di.php}');
};
