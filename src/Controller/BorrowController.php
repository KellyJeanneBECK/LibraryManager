<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Form\BorrowType;
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
        }

        return $this->render('borrow/index.html.twig', [
            'borrow_book' => $borrowBook,
            'form' => $form
        ]);
    }
}