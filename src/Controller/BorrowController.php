<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Form\BorrowType;
use App\Repository\BorrowRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/borrow')]
#[IsGranted('ROLE_USER')]
final class BorrowController extends AbstractController
{
    #[Route(name: 'app_borrow')]
    public function index(Request $request, EntityManagerInterface $entMan): Response
    {
        $borrowBook = new Borrow();
        $form = $this->createForm(BorrowType::class, $borrowBook);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowBook->setBorrowDate(new DateTimeImmutable());
            $borrowBook->setStatus('en_cours');
            $borrowBook->setUser($user);

            $entMan->persist($borrowBook);
            $entMan->flush();

            $this->addFlash('success', "Le livre a été ajouté à ma liste d'emprunt");

            return $this->redirectToRoute('app_borrow_user_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('borrow/index.html.twig', [
            'borrow_book' => $borrowBook,
            'form' => $form
        ]);
    }

    #[Route('/user/list', name: 'app_borrow_user_list')]
    public function showBorrowList(BorrowRepository $borrowRepository): Response
    {
        $user = $this->getUser();
        $borrowList = $borrowRepository->findBy(['user'=>$user],['status'=>'DESC']);

        return $this->render('borrow/userList.html.twig', [
            'borrows' => $borrowList
        ]);
    }

    #[Route('/return/{id}', name: 'app_borrow_return')]
    public function returnBook(EntityManagerInterface $entMan, Borrow $borrow): Response
    {
        $borrow->setReturnDate(new DateTimeImmutable());
        $borrow->setStatus('rendu');
        $entMan->flush();

        $this->addFlash('success', "Vous avez rendu le livre");
        
        return $this->redirectToRoute('app_borrow_user_list', [], Response::HTTP_SEE_OTHER);
    }
}