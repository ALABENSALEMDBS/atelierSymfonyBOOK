<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }



    #[Route('/afficheBook', name: 'app_aficheBook')]
    public function afficheBook(BookRepository $bookRepository): Response
    {
        $Liste= $bookRepository->findAll();
        return $this->render('book/show.html.twig', [
            'liste' =>$Liste
        ]);
    }



    #[Route('/book/nouveau', name: 'nouveau_Book')]
    public function nouveauBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            // Redirection ou message de confirmation
            return $this->redirectToRoute('app_aficheBook');
        }

        return $this->render('book/nouveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/book/{id}/delete', name: 'delete_book')]
    public function deleteAuteur(int $id, EntityManagerInterface $entityManager): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException("L'book avec l'ID $id n'existe pas.");
        }

        $entityManager->remove($book);
        $entityManager->flush();

        // Rediriger après suppression
        return $this->redirectToRoute('app_aficheBook');
    }
}
