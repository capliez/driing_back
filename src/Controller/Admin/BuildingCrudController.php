<?php

namespace App\Controller\Admin;

use App\Entity\Building;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class BuildingCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Building::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInPlural('building.namePlural')
            ->setEntityLabelInSingular(function (?Building $building, ?string $pageName) {
                return 'edit' === $pageName ? $building->getName() : 'building.nameSingul';
            })
            ->setSearchFields(['name', 'address', 'city', "postcode", "country"])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    /**
     * @param Filters $filters
     * @return Filters
     */
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name')->setLabel('building.fields.name.label'))
            ->add(TextFilter::new('address')->setLabel('building.fields.address.label'))
            ->add(TextFilter::new('city')->setLabel('building.fields.city.label'))
            ->add(TextFilter::new('country')->setLabel('building.fields.country.label'))
            ->add(BooleanFilter::new('isEnabled')->setLabel('general.fields.isEnabled.label'))
            ->add(TextFilter::new('postcode')->setLabel('building.fields.postcode.label'))
            ->add(DateTimeFilter::new('updatedAt')->setLabel('general.fields.updatedAt.label'))
            ->add(DateTimeFilter::new('createdAt')->setLabel('general.fields.createdAt.label'));
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        //Step 1
        yield TextField::new('name')->setLabel('building.fields.name.label')->setColumns('col-md-6');

        yield TextField::new('address')->setLabel('building.fields.address.label')->setColumns('col-md-6')->hideOnIndex();
        yield TextField::new('city')->setLabel('building.fields.city.label')->setColumns('col-md-6')->hideOnIndex();

        yield TextField::new('postcode')->setLabel('building.fields.postcode.label')->setColumns('col-md-6')->onlyWhenCreating();

        //Step 2
        yield AssociationField::new('guardian')
            ->setLabel('building.fields.guardian.label')
            ->setFormTypeOption('query_builder', function (UserRepository $repository) {
                return $repository->findAllGardian();
            })
            ->hideOnIndex();


        yield BooleanField::new('isEnabled')->setLabel('general.fields.isEnabled.label')->setColumns('col-md-8');

        yield DateTimeField::new('updatedAt')->setLabel('general.fields.updatedAt.label')->hideOnForm()
            ->formatValue(function ($value, Building $building) {
                return $building->getUpdatedAt() == $building->getCreatedAt() ? null : $value;
            });
        yield DateTimeField::new('createdAt')->setLabel('general.fields.createdAt.label')->hideOnForm();
    }

}
