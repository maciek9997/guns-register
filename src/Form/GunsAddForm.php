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
 * Class GunsAddForm
 * @package Form
 */
class GunsAddForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return FormBuilderInterface|void
     * Formularz dodawania broni
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        return $builder
            ->add('name', TextType::class, array(
                'constraints' => new Assert\NotBlank(),
                'label' => 'label.gun_name'
            ))
            ->add('is_blackpowder', ChoiceType::class, array(
                'required' => true,
                'choices' => ['choice.negative' => 0, 'choice.positive' => 1],
                'expanded' => true,
                'label' => 'label.is_blackpowder'
            ))
            ->add('ammunition_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['ammunitionTypes'],
                'label' => 'label.ammunition_type'
            ))
            ->add('gun_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['gunTypes'],
                'label' => 'label.gun_type',
                'expanded' => true
            ))
            ->add('reload_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['reloadTypes'],
                'label' => 'label.reload_type'
            ))
            ->add('lock_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['lockTypes'],
                'label' => 'label.lock_type'
            ))
            ->add('caliber_type', ChoiceType::class, array(
                'choices' => $options['dictionary']['caliberTypes'],
                'label' => 'label.caliber_type'
            ))
            ->add('permission', ChoiceType::class, array(
                'required' => true,
                'choices' => ['choice.negative' => 0, 'choice.positive' => 1],
                'expanded' => true,
                'label' => 'label.permission'
            ))
           ->add(
                'image_name',
                FileType::class,
                [
                    'label' => 'label.photo',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Image(
                            [
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg',
                                    'image/pjpeg',
                                    'image/jpeg',
                                    'image/pjpeg',
                                ],
                            ]
                        ),
                    ],
                ]
            )
            ->add('submit', SubmitType::class, [
                'label' => 'action.add',
            ]);
    }

    /**
     * @param OptionsResolver\OptionsResolver $resolver
     * Pobieranie danych słownikowych do powyższego formularza
     */
    public function configureOptions(OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'dictionary' => []
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