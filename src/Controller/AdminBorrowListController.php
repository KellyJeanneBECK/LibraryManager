<?php

namespace App\Controller;

use App\Repository\BorrowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/borrow/list')]
#[IsGranted('ROLE_ADMIN')]
final class AdminBorrowListController extends AbstractController
{
    #[Route(name: 'app_admin_borrow_list')]
    public function index(BorrowRepository $borrowRepository): Response
    {
        $inProgress = "en_cours";
        $borrowedBooks = $borrowRepository->findBy(['status' => $inProgress],['book'=>'ASC']);

        return $this->render('admin_borrow_list/index.html.twig', [
            'borrowedBooks' => $borrowedBooks,
        ]);
    }
}