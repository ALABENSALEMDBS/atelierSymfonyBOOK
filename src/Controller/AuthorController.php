<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{

    private array $authors = [
        1 => ['id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        2 => ['id' => 2, 'picture' => '/images/william.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        3 => ['id' => 3, 'picture' => '/images/Taha-Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];


    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route(path: '/listeauthor', name: 'list_author')]
    public function listAuthors(): Response
    {
        /*$authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha-Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
            );*/
            $lenght = count($this->authors);
        return $this-> render('author/listeAuthors.html.twig',['Authors'=> $this->authors,
        'n_a'=>$lenght
    
        ]);
    }


    #[Route('/author/{id}', name: 'author_details')]
public function authorDetails(int $id): Response
{
    /*$authors = [
        1 => ['id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        2 => ['id' => 2, 'picture' => '/images/william.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        3 => ['id' => 3, 'picture' => '/images/Taha-Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];*/

    $author = $this->authors[$id];

    return $this->render('author/showAuthor.html.twig', [
        'author' => $author,
    ]);
}


    #[Route(path: '/read', name: 'list_read')]
    public function readAuthor(AuthorRepository $authorRepository):Response
        {
            $liste =$authorRepository ->findAll();
            return $this->render('author/read.html.twig', ['liste'=>$liste]);
        }

    #[Route(path:'/addAuthorStatic' , name:'add_A_S')]
    public function addAuthorStatic(EntityManagerInterface $entityManager):Response
    {
        $author = new Author();
        $author->setEmail('sana@esprit.tn');
        $author->setUsername('sana');

        $entityManager->persist($author);
        $entityManager->flush();

        return new Response('Auteur ajouté avec succès !');
    }


    #[Route('/auteur/nouveau', name: 'nouveau_auteur')]
    public function nouveauAuteur(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Author();
        $form = $this->createForm(AuthorType::class, $auteur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder l'auteur dans la base de données
            $entityManager->persist($auteur);
            $entityManager->flush();

            // Redirection ou message de confirmation
            return $this->redirectToRoute('list_read');
        }

        return $this->render('author/nouveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/auteur/{id}/edit', name: 'edit_auteur')]
    public function editAuteur(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'auteur avec l'ID
        $auteur = $entityManager->getRepository(Author::class)->find($id);

        if (!$auteur) {
            throw $this->createNotFoundException("L'auteur avec l'ID $id n'existe pas.");
        }

        $form = $this->createForm(AuthorType::class, $auteur);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('list_read');
        }

        // Rendre la vue du formulaire de modification
        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'auteur' => $auteur,
        ]);
    }


    #[Route('/auteur/{id}/delete', name: 'delete_auteur')]
    public function deleteAuteur(int $id, EntityManagerInterface $entityManager): Response
    {
        $auteur = $entityManager->getRepository(Author::class)->find($id);

        if (!$auteur) {
            throw $this->createNotFoundException("L'auteur avec l'ID $id n'existe pas.");
        }

        $entityManager->remove($auteur);
        $entityManager->flush();

        // Rediriger après suppression
        return $this->redirectToRoute('list_read');
    }

}
