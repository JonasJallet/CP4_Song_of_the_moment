<?php

namespace App\Infrastructure\Form;

use App\Infrastructure\Persistence\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', EmailType::class)
            ->add('password', PasswordType::class, [
                'help' =>
                    'Le mot de passe doit avoir 12 caractères dont une lettre majuscule, une minuscule et un chiffre',
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->add('birthDay', IntegerType::class, [
                'label' => 'Jour',
                'required' => true,
            ])
            ->add('birthMonth', IntegerType::class, [
                'label' => 'Mois',
                'required' => true,
            ])
            ->add('birthYear', IntegerType::class, [
                'label' => 'Année',
                'required' => true,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes',
                    ]),
                ],
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
