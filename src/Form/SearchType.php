<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Range;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departement', NumberType::class, [
                'required'=>false,
             'invalid_message' =>'Le département doit être un nombre',
                'constraints' => [
                      new Range([
                        'min' => 01,
                        'max' => 99,
                        'notInRangeMessage' => 'Département non valide',
                        'invalidMessage'=> 'Département non valide',
                    ]),
                 ],
             ])
            ->add('nom',TextType::class, [
               'required'=>false,
            ])
            ->add('secteur_activite',TextType::class, [
               'required'=>false,
            ])
            ->add('formation',TextType::class,[
                'required'=>false,
            ])
            ->add('email', TextType::class,[
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'csrf_protection' => false,
                'allow_extra_fields' => true
        ]);
    }
}
