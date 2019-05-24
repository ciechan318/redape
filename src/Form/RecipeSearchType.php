<?php

namespace App\Form;

use App\Service\RecipeManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeSearchType extends AbstractType
{

    /**
     * @var RecipeManager
     */
    private $recipeManager;

    public function __construct(RecipeManager $recipeManager)
    {
        $this->recipeManager = $recipeManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phrase', TextType::class, ['required' => false, 'label' => false, 'attr' => ['placeholder' => 'placeholder_phrase']])
            ->add('ingredients', ChoiceType::class, ['choices' => $this->recipeManager->getIngredientChoices(), 'multiple' => true, 'attr' => ['class' => 'chosen-select']]); //@TODO translation for placeholder
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
        ]);
    }
}
