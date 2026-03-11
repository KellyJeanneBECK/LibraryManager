<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Borrow;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('book', EntityType::class, [
                'placeholder' => "Selectionnez un livre",
                'class' => Book::class,
                // query_builder to sort books alphabetically and exclude the ones with no stock
                'query_builder' => function (EntityRepository $entRepo): QueryBuilder {
                    return $entRepo->createQueryBuilder('book')
                        ->where('book.stock > 0')
                        ->orderBy('book.title', 'ASC');
                },
                'choice_label' => function (Book $book) {
                    return '"'.$book->getTitle().'" : '.$book->getStock().' disponible(s)';
                },
            ])
            ->add('duration', ChoiceType::class, [
                'placeholder' => "Selectionnez une durée",
                'choices' => [
                    "2 semaines" => 2,
                    "3 semaines" => 3,
                    "4 semaines" => 4,
                ],
                'mapped' => false, // 'duration' is not a property of Borrow Entity
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}