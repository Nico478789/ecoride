<?php

namespace App\Controller;

use App\Document\Comment;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CommentType;

final class CommentController extends AbstractController
{
    #[Route('/comment/create', name: 'app_comment_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, DocumentManager $dm)
    {
        $comment = new Comment();
        $user = $this->getUser();
        if ($user) {
            $comment->setAuthor($user);
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($comment);
            $dm->flush();

            return $this->redirectToRoute('app_comment_show');
        }

        return $this->render('comment/create.html.twig', [
            'formview' => $form->createView(),
        ]);
    }

    #[Route('/comment/show', name: 'app_comment_show', methods: ['GET'])]
    public function showAction(DocumentManager $dm)
    {
        $comment = $dm->getRepository(Comment::class)->findAll();

        if (! $comment) {
            throw $this->createNotFoundException('Pas de commentaires trouvés.');
        }

        return $this->render('comment/show.html.twig', [
            'comments' => $comment,

        ]);
    }
}
