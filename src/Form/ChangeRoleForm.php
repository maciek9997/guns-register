<?php
/**
 * ChangeRole form.
 * Formularz zmiany roli
 */

/**
 * This file is part of the Symfony package.
 */
namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class ChangeRoleForm
 */
class ChangeRoleForm extends AbstractType
{
    /**
     * Formularz zmiany roli
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class, array(
                'choices' => ['label.choice_admin' => 1, 'label.choice_user' => 2],
                'label' => 'label.role',
            ));
    }

    /**
     * Name of the form in html
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'login_type';
    }
}
