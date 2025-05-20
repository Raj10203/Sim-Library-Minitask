<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoanForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dueAt', null, [
                'widget' => 'single_text',
            ])
            ->add('returnedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
