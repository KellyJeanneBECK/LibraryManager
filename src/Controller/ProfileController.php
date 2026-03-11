<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
final class ProfileController extends AbstractController
{
    #[Route(name: 'app_profile')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $userProfile = $userRepository->find($user);

        return $this->render('profile/index.html.twig', [
            'userProfile' => $userProfile,
        ]);
    }

    #[Route('/edit', name:'app_profile_edit')]
    public function editProfile(Request $request, UserRepository $userRepository, EntityManagerInterface $entMan): Response
    {
        $user = $userRepository->find($this->getUser());
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entMan->flush();

            $this->addFlash('success', "Les modifications ont été enregistrées");

            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('profile/editProfile.html.twig', [
            'form' => $form,
        ]);
    }
}