<?php
/**
 * Created by PhpStorm.
 * Date: 03.08.18
 * Time: 12:55
 */

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Validator\Constraints\UniqueEmail;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('name', TextType::class, array(
                'constraints' => new Assert\NotBlank(),
                'label' => 'label.name'
            ))
            ->add('surname', TextType::class, array(
                'constraints' => new Assert\NotBlank(),
                'label' => 'label.surname'
            ))
            ->add('address', TextType::class, array(
                'constraints' => new Assert\NotBlank(),
                'label' => 'label.address'
            ))
            ->add('login', TextType::class, array(
                'constraints' => [
                    new Assert\Email(),
                    new UniqueEmail([
                        'repository' => $options['user_repository']
                    ])
                ],
                'label' => 'label.email'
            ))
            ->add('phone', TextType::class, array(
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex("/^(\(0\))?[0-9]{9}$/")],
                'label' => 'label.phone'
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'label.password_register'
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'action.register',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'user_repository' => null,
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'register_type';
    }
}