<?php

namespace App\Controller;

use App\Document\Comment;
use App\Entity\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommentController extends AbstractController
{
    #[Route('/comment/create', name: 'app_comment_create', methods: ['GET', 'POST'])]
    public function createAction(DocumentManager $dm, EntityManagerInterface $em)
    {
        $comment = new Comment();
        $user = $this->getUser();
        $target = $em->getRepository(User::class)->findOneBy(['id' => 5]);
        $comment->settitle('titre 2');
        $comment->setcontent('encore du contenu');
        $comment->setAuthor($user);
        $comment->setTarget($target);

        $dm->persist($comment);
        $dm->flush();

        return new Response('Created comment id ' . $comment->getId());
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
