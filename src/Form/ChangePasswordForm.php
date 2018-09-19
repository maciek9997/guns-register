<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

/**
 * Class ChangePasswordForm
 */
class ChangePasswordForm extends AbstractType
{
    /**
     * Formularz zmiany hasła
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'label.new_password'),
                'second_options' => array('label' => 'label.new_password_repeat'),
                'invalid_message' => 'message.passwords_must_match',
            ));
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'login_type';
    }
}
