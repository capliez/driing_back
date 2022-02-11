<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInPlural('user.namePlural')
            ->setEntityLabelInSingular(function (?User $user, ?string $pageName) {
                return 'edit' === $pageName ? $user->getFullName() : 'user.nameSingul';
            })
            ->setFormOptions(['validation_groups' => ['Default', 'registration']], [])
            ->setSearchFields(['lastName', 'firstName', 'email', "phone"])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }


    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $result = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        /** @var User $userCurrent */
        $userCurrent = $this->getUser();
        $result->andWhere('entity.id != :id')->setParameter('id', $userCurrent->getId());

        return $result;
    }

    /**
     * @param Filters $filters
     * @return Filters
     */
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('lastName')->setLabel('user.fields.lastName.label'))
            ->add(TextFilter::new('firstName')->setLabel('user.fields.firstName.label'))
            ->add(TextFilter::new('email')->setLabel('user.fields.email.label'))
            ->add(BooleanFilter::new('isEnabled')->setLabel('general.fields.isEnabled.label'))
            ->add(TextFilter::new('phone')->setLabel('user.fields.phone.label'))
            ->add(DateTimeFilter::new('updatedAt')->setLabel('general.fields.updatedAt.label'))
            ->add(DateTimeFilter::new('createdAt')->setLabel('general.fields.createdAt.label'));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::DELETE)
            ->addBatchAction(Action::new('enabledAccount', 'Activer le compte')
                ->linkToCrudAction('enabledUsers')
                ->addCssClass('btn btn-success')
                ->setIcon('fa fa-unlock'))
            ->addBatchAction(Action::new('disabledAccount', 'Désactiver le compte')
                ->linkToCrudAction('disabledUsers')
                ->addCssClass('btn btn-danger')
                ->setIcon('fa fa-lock'));
    }

    public function disabledUsers(BatchActionDto $batchActionDto)
    {
        $entityManager = $this->doctrine->getManagerForClass($batchActionDto->getEntityFqcn());
        foreach ($batchActionDto->getEntityIds() as $id) {
            $user = $entityManager->getRepository($batchActionDto->getEntityFqcn())->findOneBy(['id' => $id]);
            $user->getIsEnabled() && $user->setIsEnabled(false);
        }

        $entityManager->flush();

        return $this->redirect($batchActionDto->getReferrerUrl());
    }

    /**
     * Activer les utilisateurs sélectionner
     * @param BatchActionDto $batchActionDto
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enabledUsers(BatchActionDto $batchActionDto)
    {
        $entityManager = $this->doctrine->getManagerForClass($batchActionDto->getEntityFqcn());
        foreach ($batchActionDto->getEntityIds() as $id) {
            $user = $entityManager->getRepository($batchActionDto->getEntityFqcn())->findOneBy(['id' => $id]);
            !$user->getIsEnabled() && $user->setIsEnabled(true);
        }

        $entityManager->flush();

        return $this->redirect($batchActionDto->getReferrerUrl());
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        //Step 1
        yield TextField::new('fullName')->setLabel('general.fields.fullName.label')->setColumns('col-md-6')->onlyOnIndex();

        yield TextField::new('firstName')->setLabel('user.fields.firstName.label')->setColumns('col-md-6')->hideOnIndex();
        yield TextField::new('lastName')->setLabel('user.fields.lastName.label')->setColumns('col-md-6')->hideOnIndex();

        yield EmailField::new('email')->setLabel('user.fields.email.label')->setColumns('col-md-8')->hideOnIndex();
        yield TextField::new('password')->setFormType(PasswordType::class)->setLabel('user.fields.password.label')->setColumns('col-md-6')->onlyWhenCreating();
        yield TelephoneField::new('phone')->setLabel('user.fields.phone.label')->setColumns('col-md-6')->hideOnIndex();

        //Step 2
        yield AssociationField::new('language')
            ->setLabel('user.fields.language.label')->hideOnIndex();

        yield AssociationField::new('userRole')
            ->setLabel('user.fields.role.label')
            ->formatValue(function ($value, User $user) {
                return $user->getUserRole() ? $user->getUserRole()->getLabel() : 'Utilisateur';
            });

        yield BooleanField::new('isEnabled')->setLabel('general.fields.isEnabled.label')->setColumns('col-md-8');

        yield DateTimeField::new('updatedAt')->setLabel('general.fields.updatedAt.label')->hideOnForm()
            ->formatValue(function ($value, User $user) {
                return $user->getUpdatedAt() == $user->getCreatedAt() || $user->getUpdatedAt() == $user->getLastLoginAt() ? null : $value;
            });
        yield DateTimeField::new('lastLoginAt')->setLabel('general.fields.lastLoginAt.label')->hideOnForm();
    }



}
