<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Track;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('album', EntityType::class, [
                'class' => Album::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('track', EntityType::class, [
                'class' => Track::class,
                'choice_label' => 'id',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
