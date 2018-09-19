<?php


namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class GunsEditForm
 */
class GunsEditForm extends AbstractType
{
    /**
     * Formularz edytowania broni
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return FormBuilderInterface|void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        return $builder
            ->add('name', TextType::class, array(
                'constraints' => new Assert\NotBlank(),
                'label' => 'label.gun_name',
            ))
            ->add('is_blackpowder', ChoiceType::class, array(
                'required' => true,
                'choices' => ['choice.negative' => 0, 'choice.positive' => 1],
                'expanded' => true,
                'label' => 'label.is_blackpowder',
            ))
            ->add('ammunition_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['ammunitionTypes'],
                'label' => 'label.ammunition_type',
            ))
            ->add('gun_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['gunTypes'],
                'label' => 'label.gun_type',
                'expanded' => true,
            ))
            ->add('reload_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['reloadTypes'],
                'label' => 'label.reload_type',
            ))
            ->add('lock_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['lockTypes'],
                'label' => 'label.lock_type',
            ))
            ->add('caliber_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['caliberTypes'],
                'label' => 'label.caliber_type',
            ))
            ->add('permission', ChoiceType::class, array(
                'required' => true,
                'choices' => ['choice.negative' => 0, 'choice.positive' => 1],
                'expanded' => true,
                'label' => 'label.permission',
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'action.edit',
            ]);
    }

    /**
     * Pobieranie danych słownikowych do powyższego formularza
     * @param OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'dictionary' => [],
        ]);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'register_type';
    }
}
