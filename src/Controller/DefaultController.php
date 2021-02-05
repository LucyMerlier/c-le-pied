<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Comment;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use App\Form\PictureType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

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
     * @Route("/favoris", name="favorites")
     * @IsGranted("ROLE_CONTRIBUTOR")
     */
    public function favorites(): Response
    {
        return $this->render('favorites.html.twig');
    }

    /**
     * @Route("/poster-une-photo", name="add_picture")
     * @IsGranted("ROLE_CONTRIBUTOR")
     */
    public function addPicture(Request $request, EntityManagerInterface $manager): Response
    {
        $picture = new Picture();
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture->setOwner($this->getUser());

            $manager->persist($picture);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('add_picture.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/photo/{id}", name="show_picture", requirements={"id"="\d+"})
     */
    public function showPicture(Picture $picture, EntityManagerInterface $entityManager, Request $request): Response
    {
        unset($comment);
        unset($form);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPicture($picture);
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        unset($comment);
        unset($form);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('show_picture.html.twig', [
            'picture' => $picture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("photo/{id}/supprimer", name="delete_picture", methods={"DELETE"})
     * @IsGranted("ROLE_CONTRIBUTOR")
     */
    public function deletePicture(Request $request, Picture $picture, EntityManagerInterface $entityManager): Response
    {
        if (!($this->getUser() == $picture->getOwner()) && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Vous ne pouvez pas supprimer les photos des autres utilisateurs');
        }

        if ($this->isCsrfTokenValid('delete'.$picture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($picture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
    }

    /**
     * @Route("commentaire/{id}/supprimer", name="delete_comment", methods={"DELETE"})
     * @IsGranted("ROLE_CONTRIBUTOR")
     */
    public function deleteComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if (!($this->getUser() == $comment->getAuthor()) && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            throw new AccessDeniedException('Vous ne pouvez pas supprimer les commentaires des autres utilisateurs');
        }

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_show_picture', ['id' => $comment->getPicture()->getId()]);
    }

    /**
     * @Route("/photo/{id}/like", name="like", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTRIBUTOR")
     */
    public function switchLike(Picture $picture, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()->hasLiked($picture)) {
            $this->getUser()->addLike($picture);
        } else {
            $this->getUser()->removeLike($picture);
        }

        $entityManager->flush();

        return $this->json([
            'hasLiked' => $this->getUser()->hasLiked($picture),
        ]);
    }
}
