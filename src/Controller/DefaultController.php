<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="app_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route(name="home")
     */
    public function home(PictureRepository $pictureRepository): Response
    {
        return $this->render('home.html.twig', [
            'pictures' => $pictureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/profil/{username}", name="profile", requirements={"username"="[a-zA-Z0-9\-]+$"})
     */
    public function profile(User $user): Response
    {
        return $this->render('profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/poster-une-photo", name="add_picture")
     */
    public function addPicture(): Response
    {
        return $this->render('add_picture.html.twig');
    }

    /**
     * @Route("/photo/{id}", name="show_picture", requirements={"id"="\d+"})
     */
    public function showPicture(Picture $picture): Response
    {
        return $this->render('show_picture.html.twig', [
            'picture' => $picture,
        ]);
    }
}
