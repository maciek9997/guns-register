<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 * Class ChangePasswordForm
 * @package Form
 */
class ChangeRoleForm extends AbstractType
{
    /**
     * Formularz zmiany hasła
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class, array(
                'choices' => ['Admin' => 1, 'User' => 2],
                'label' => 'label.role'
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