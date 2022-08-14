<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\Types\RoleType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Users')
            ->setPageTitle(Crud::PAGE_NEW, 'Create new user')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit user')
            ->setEntityLabelInSingular('User')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->disable(Action::DELETE);

        return parent::configureActions($actions);
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        /** @var User $user */
        $user = $this->getUser();

        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($user->hasRole(User::ROLE_ADMIN)) {
            return $qb;
        }

        $qb
            ->andWhere('entity.roles NOT LIKE :roleAdmin AND entity.roles NOT LIKE :roleModerator')
            ->setParameter('roleAdmin', '%"'.User::ROLE_ADMIN.'"%')
            ->setParameter('roleModerator', '%"'.User::ROLE_MODERATOR.'"%')
        ;

        return $qb;
    }

    /**
     * @param User $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance->getPlainPassword()) {
            parent::updateEntity($entityManager, $entityInstance);

            return;
        }

        /** @var string $plainPassword */
        $plainPassword = $entityInstance->getPlainPassword();
        $encodedPassword = $this->hasher->hashPassword($entityInstance, $plainPassword);
        $entityInstance->setPassword($encodedPassword);

        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * @param User $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance->getPlainPassword()) {
            return;
        }

        /** @var string $plainPassword */
        $plainPassword = $entityInstance->getPlainPassword();
        $encodedPassword = $this->hasher->hashPassword($entityInstance, $plainPassword);
        $entityInstance->setPassword($encodedPassword);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        $roleChoices = array_flip(User::ROLES);

        if (!$this->getUser()->hasRole(User::ROLE_ADMIN)) {
            $roleChoices = array_filter(
                $roleChoices,
                fn ($role) => !\in_array($role, [User::ROLE_ADMIN, User::ROLE_MODERATOR], true)
            );
        }

        $statusChoices = array_flip(User::STATUSES);

        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('username', 'Username'),
            EmailField::new('email', 'Email'),
            ChoiceField::new('roles', 'Roles')
                ->setChoices($roleChoices)
                ->allowMultipleChoices(false)
                ->setFormType(RoleType::class)
                ->setRequired(false),
            TextField::new('plainPassword', 'Password')
                ->setRequired(Crud::PAGE_NEW === $pageName)
                ->hideOnIndex(),
            DateTimeField::new('lastLogin', 'Last login')
                ->onlyOnIndex(),
            DateTimeField::new('registeredAt', 'Registered at')
                ->onlyOnIndex(),
            DateTimeField::new('updatedAt', 'Updated at')
                ->onlyOnIndex(),
            TextField::new('phone', 'Phone number'),
            BooleanField::new('confirmed', 'Confirmed'),
            ChoiceField::new('status', 'Statuses')
                ->setChoices($statusChoices)
                ->allowMultipleChoices(false)
                ->setRequired(true),
        ];
    }
}
