<?php


namespace App\Admin\Administration;

use App\Entity\Recipe;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\NumberFilter;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class RecipeAdmin extends AbstractAdministrationAdmin
{

    protected $baseRoutePattern = self::ROUTE_PREFIX . 'recipes';

    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        $object->setUser($this->clientManager->getUser());

        return $object;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('description')//@TODO WYSIWYG editor
            ->add('preparationTime', null, ['help' => 'help_label_preparationType'])
            ->add('type', ChoiceType::class, ['choices' => Recipe::getTypes()])
            ->add('ingredientQuantities', CollectionType::class, [
                'by_reference' => false,
            ], [
                    'label' => false,
                    'edit' => 'inline',
                    'inline' => 'table',
                ]
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('user', null, ['admin_code' => UserUserAdmin::class])
            ->add('preparationTime', NumberFilter::class)//@TODO comparison characters and integer
            ->add('type', ChoiceFilter::class, [], ChoiceType::class, ['choices' => Recipe::getTypes()]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('user', null, ['admin_code' => UserUserAdmin::class])
            ->add('humanType', null, ['template' => 'admin/trans.html.twig', 'label' => 'Type']);
    }
}