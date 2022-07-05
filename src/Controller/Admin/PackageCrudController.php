<?php

namespace App\Controller\Admin;

use App\Entity\Package;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class PackageCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Package::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInPlural('package.namePlural')
            ->setEntityLabelInSingular(function (?Package $package, ?string $pageName) {
                return 'edit' === $pageName ? $package->getResident()->getLastName() : 'package.nameSingul';
            })
            ->setSearchFields(['resident', 'guardian'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    /**
     * @param Filters $filters
     * @return Filters
     */
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isEnabled')->setLabel('general.fields.isEnabled.label'))
            ->add(DateTimeFilter::new('updatedAt')->setLabel('general.fields.updatedAt.label'))
            ->add(DateTimeFilter::new('createdAt')->setLabel('general.fields.createdAt.label'));
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        yield NumberField::new('nbPackage')->setLabel('package.fields.nbPackage.label')->setColumns('col-md-6');
        yield BooleanField::new('isHandedOver')->setLabel('package.fields.isHandedOver.label')->setColumns('col-md-8');
        yield BooleanField::new('isBulky')->setLabel('package.fields.isBulky.label')->setColumns('col-md-8');

        yield AssociationField::new('building')
            ->setLabel('package.fields.building.label')
            ->hideOnIndex();
        yield AssociationField::new('resident')
            ->setLabel('package.fields.resident.label');
        yield AssociationField::new('guardian')
            ->setLabel('package.fields.guardian.label')
            ->hideOnIndex();

        yield DateTimeField::new('updatedAt')->setLabel('general.fields.updatedAt.label')->hideOnForm()
            ->formatValue(function ($value, Package $package) {
                return $package->getUpdatedAt() == $package->getCreatedAt() ? null : $value;
            });
        yield DateTimeField::new('createdAt')->setLabel('general.fields.createdAt.label')->hideWhenCreating();
    }

}
