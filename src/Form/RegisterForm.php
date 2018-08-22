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


class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('name', TextType::class, array(
                'constraints' => new Assert\NotBlank()
            ))
            ->add('surname', TextType::class, array(
                'constraints' => new Assert\NotBlank()
            ))
            ->add('address', TextType::class, array(
                'constraints' => new Assert\NotBlank()
            ))
            ->add('login', TextType::class, array(
                'constraints' => new Assert\Email(),
                'label' => 'Email'
            ))
            ->add('phone', TextType::class, array(
                'constraints' => new Assert\NotBlank()
            ))
            ->add('password', PasswordType::class, array())
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ]);
    }

    public function getBlockPrefix()
    {
        return 'register_type';
    }
}