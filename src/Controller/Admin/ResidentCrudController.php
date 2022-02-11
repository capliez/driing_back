<?php

namespace App\Controller\Admin;

use App\Entity\Building;
use App\Entity\Resident;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class ResidentCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Resident::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInPlural('resident.namePlural')
            ->setEntityLabelInSingular(function (?Resident $resident, ?string $pageName) {
                return 'edit' === $pageName ? $resident->getLastName() : 'resident.nameSingul';
            })
            ->setSearchFields(['lastName', 'phone', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    /**
     * @param Filters $filters
     * @return Filters
     */
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('lastName')->setLabel('resident.fields.lastName.label'))
            ->add(TextFilter::new('phone')->setLabel('resident.fields.phone.label'))
            ->add(TextFilter::new('email')->setLabel('resident.fields.email.label'))
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
        yield TextField::new('lastName')->setLabel('resident.fields.lastName.label')->setColumns('col-md-6');
        yield TelephoneField::new('phone')->setLabel('resident.fields.phone.label')->setColumns('col-md-6')->hideOnIndex();
        yield TextField::new('email')->setLabel('resident.fields.email.label')->setColumns('col-md-6')->hideOnIndex();
        yield AssociationField::new('building')
            ->setLabel('resident.fields.building.label')
            ->hideOnIndex();
        yield BooleanField::new('isEnabled')->setLabel('general.fields.isEnabled.label')->setColumns('col-md-8');
        yield DateTimeField::new('updatedAt')->setLabel('general.fields.updatedAt.label')->hideOnForm()
            ->formatValue(function ($value, Resident $resident) {
                return $resident->getUpdatedAt() == $resident->getCreatedAt() ? null : $value;
            });
        yield DateTimeField::new('createdAt')->setLabel('general.fields.createdAt.label')->hideOnForm();
    }

}
