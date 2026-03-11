<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user/manager')]
#[IsGranted('ROLE_ADMIN')]
final class UserManagerController extends AbstractController
{
    #[Route(name: 'app_user_manager')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user_manager/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_user_delete')]
    public function deleteUser($id, User $user, EntityManagerInterface $entMan, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        foreach ($user->getBorrow() as $borrow) {
            $entMan->remove($borrow);
        }

        $entMan->remove($user);
        $entMan->flush();

        $this->addFlash('danger', "L'utilisateur a bien été supprimé");

        return $this->redirectToRoute('app_user_manager');
    }
}