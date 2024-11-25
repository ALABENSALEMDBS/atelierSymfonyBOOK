<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', null, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le titre est requis.']),
                new Assert\Length([
                    'max' => 10,
                    'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ])
        ->add('publicationDate', null, [
            'widget' => 'single_text',
            'data' => new \DateTime(),
            'constraints' => [
                new Assert\NotBlank(['message' => 'La date de publication est requise.']),
            ],
        ])
        ->add('published', null, [
            'constraints' => [
                new Assert\NotNull(['message' => 'Indiquez si le livre est publié.']),
            ],
        ])
        ->add('author', EntityType::class, [
            'class' => Author::class,
            'choice_label' => 'id',
            'constraints' => [
                new Assert\NotNull(['message' => 'Un auteur doit être sélectionné.']),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
