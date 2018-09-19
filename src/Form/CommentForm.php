<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class CommentForm
 */
class CommentForm extends AbstractType
{
    /**
     * Formularz dodawania komentarzy
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return FormBuilderInterface|void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('comment', TextareaType::class, array(
                'constraints' => new Assert\NotBlank(),
                'label' => 'label.comment',
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'action.comment_add',
            ]);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'todo_type';
    }
}
