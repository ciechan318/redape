<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', null, ['attr' => ['class' => 'ckeditor simple']])//@TODO ckeditor fix
            ->add('preparationTime', null, ['help' => 'help_label_preparationType'])
            ->add('type', ChoiceType::class, ['choices' => Recipe::getTypes()])
            ->add('ingredientQuantities', CollectionType::class, [
                'label'=>false,
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => IngredientQuantityType::class,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
