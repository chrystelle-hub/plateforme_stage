<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AjoutContactType extends AbstractType
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
            ->add('fonction',TextType::class, [
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir une fonction',
                    ]),
                ],
            ])
            ->add('tel',TelType::class, [
                'required' => false
            ])
            ->add('mail', EmailType::class, [
                'required' => false
            ])
            ->add('linkedin',TextType::class, [
                'required'=>false,
                
            ])
            ->add('entreprise',NumberType::class, [
                'mapped'=>false,
             'invalid_message' =>'erreur',
                'constraints' => [
                     new NotBlank([
                        'message' => 'Veillez saisir une entreprise',
                    ]),
                 ],
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
             'csrf_protection' => false,
        ]);
    }
}
