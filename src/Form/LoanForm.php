<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoanForm extends AbstractType
{
    public function __construct(private Security $security)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($this->security->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('dueAt', null, [
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'required' => false,
                ])
                ->add('book', EntityType::class, [
                    'class' => Book::class,
                    'choice_label' => 'title',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('b')
                            ->where('b.isAvailable = :available')
                            ->setParameter('available', true)
                            ->orderBy('b.title', 'ASC');
                    },
                    'attr' => [
                        'class' => 'select2-dropdown-single',
                    ]
                ])
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'email',
                    'attr' => [
                        'class' => 'select2-dropdown-single',
                    ]
                ])
            ;
        }
        $builder->add('submit', SubmitType::class, [
            'label' =>  $options['submit_label'],
            'attr' => [
                'class' => 'btn btn-primary mt-2',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
            'submit_label' => 'Borrow Book',
        ]);
    }
}
