<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AjoutCandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('moyen', NumberType::class, [
             'invalid_message' =>'erreur',
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir un moyen',
                    ])
                 ],
             ])
            ->add('etat', NumberType::class, [
             'invalid_message' =>'erreur',
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir un etat',
                    ])
                     ],
             ])
            ->add('reponse', NumberType::class, [
             'invalid_message' =>'erreur',
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir une reponse',
                    ])
                     ],
             ])
            ->add('delai_reponse', NumberType::class, [
             'invalid_message' =>'erreur',
             'required'=>false,
                
             ])
            ->add('entreprise', NumberType::class, [
                'mapped'=>false,
             'invalid_message' =>'erreur',
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir une entreprise',
                    ])
                     ],
             ])
            ->add('formation',NumberType::class,[
                'mapped'=>false,
                'invalid_message' =>'erreur',
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir une formation',
                    ])
                     ],
            ])

            ->add('dateEnvoieCandidature', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'empty_data' => null,
                'invalid_message' =>'veuillez saisir une date au bon format',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
             'csrf_protection' => false,
        ]);
    }
}
