<?php

namespace App\Form;

use App\Entity\RecipeImage;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RecipeImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, ['required' => false, 'download_label' => 'action_downloadFullSize', 'imagine_pattern' => 'admin_preview', 'allow_delete' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => RecipeImage::class,
        ]);
    }
}
