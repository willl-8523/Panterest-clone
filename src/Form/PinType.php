<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    // FormBuilder
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
            'label' => 'Image (JPG or PNG file)',
            'required' => false,
            'allow_delete' => true,
            'download_uri' => false,
            'imagine_pattern' => 'squared_thumbnail_small',
        ])
            ->add('title')
            ->add('description')
        ;
    }

    // Configurer les options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
