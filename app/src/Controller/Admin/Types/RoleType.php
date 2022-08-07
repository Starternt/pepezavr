<?php

declare(strict_types=1);

namespace App\Controller\Admin\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->addModelTransformer(
                new CallbackTransformer(
                    fn ($originalRoles) => ($originalRoles) ? $originalRoles[0] : null,
                    fn ($submmitedRoles) => array_filter([$submmitedRoles])
                )
            )
        ;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
