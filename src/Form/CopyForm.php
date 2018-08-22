<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class CopyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder;

    }

    public function getBlockPrefix()
    {
        return 'todo_type';
    }
}