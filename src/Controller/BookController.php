<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookHistory;
use App\Form\BookHistoryType;
use App\Form\BookType;
use App\Form\BookUpdateType;
use App\Repository\BookHistoryRepository;
use App\Repository\BookRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book')]
#[IsGranted('ROLE_ADMIN')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockHistory = new BookHistory();
            $stockHistory->setBook($book);
            $stockHistory->setQuantity($book->getStock());
            $stockHistory->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($stockHistory);
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', "Le livre a été ajouté à la collection");

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookUpdateType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "Les modifications ont été enregistrées");

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->getString('_token'))) {
            foreach ($book->getBorrow() as $borrow) {
                $entityManager->remove($borrow);
            }
            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash('danger', "Le livre a été supprimé de la collection");
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add/stock/{id}', name: 'app_book_add_stock')]
    public function addStock($id, EntityManagerInterface $entMan, Request $request, BookRepository $bookRepository): Response
    {
        $addStock = new BookHistory();
        $form = $this->createForm(BookHistoryType::class, $addStock);
        $form->handleRequest($request);

        $book = $bookRepository->find($id);

        if($form->isSubmitted() && $form->isValid()) {
            if($addStock->getQuantity()>0) {
                $newStock = $book->getStock() + $addStock->getQuantity();
                $book->setStock($newStock);
                $addStock->setCreatedAt(new DateTimeImmutable());
                $addStock->setBook($book);

                $entMan->persist($addStock);
                $entMan->flush();

                $this->addFlash('success', "Le nouveau stock a été ajouté");
                return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('danger', "Le nouveau stock ne devrait pas être inférieur à 0");
                return $this->redirectToRoute('app_book_add_stock', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('book/addStock.html.twig',
            ['form' => $form->createView(),
            'book' => $book]
        );
    }

    #[Route('/add/stock/{id}/history', name:'app_book_stock_history', methods:['GET'])]
    public function showProductHistory($id, BookRepository $bookRepository, BookHistoryRepository $bookHistoryRepository): Response
    {
        $book = $bookRepository->find($id);
        
        $bookHistory = $bookHistoryRepository->findBy(['book'=>$book],['id'=>'DESC']);

        return $this->render('book/showBookHistory.html.twig', [
            'book'=>$book,
            'bookHistories'=>$bookHistory
        ]);
    }
}