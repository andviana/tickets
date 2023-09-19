<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class,[
                'mapped'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Informe o nome completo',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'mapped'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Informe um Email',
                    ]),
                ],
            ])
            ->add('cpf', TextType::class, [
                'mapped'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Informe um CPF',
                    ]),
                    new Length([
                    'min'=>11,
                    'minMessage'=>'O CPF deve conter {{limit}} caracteres',
                    'max'=>11,
                    ])
                ]
            ])
            ->add('username')
            ->add('agreeTerms', CheckboxType::class, [
                'label'=>'declaro que aceito os termos e condições para utilização do software',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Você deve aceitar os termos para continuar.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, informe um asenha',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Sua senha deve ter pelo menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
