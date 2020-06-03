<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotBlank;

class AjoutEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, [
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir un nom',
                    ]),
                ],
            ])
            ->add('secteur_activite',TextType::class, [
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir un secteur d\'activité',
                    ]),
                ],
            ])
            ->add('adresse',TextType::class, [
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir une adresse',
                    ]),
                ],
            ])
            ->add('code_postal', NumberType::class, [
             'invalid_message' =>'Le code postal doit être un nombre',
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir un code postal',
                    ]),
                      new Range([
                        'min' => 00000,
                        'max' => 99999,
                        'notInRangeMessage' => 'Code postal non valide',
                        'invalidMessage'=> 'Code postal non valide',
                    ]),
                 ],
             ])
            ->add('ville',TextType::class, [
                'constraints' => [
                    new NotBlank([
                       'message' => 'Veillez saisir une ville',
                   ]),
               ],  
            ])
            ->add('tel',TelType::class, [
                'required' => false,
                'empty_data' => 0
            ])
            ->add('mail', EmailType::class, [
                'required' => false,
                'empty_data' => 0
            ])

             
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
             'csrf_protection' => false,
        ]);
    }
}
