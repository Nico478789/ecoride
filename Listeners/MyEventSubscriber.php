<?php

use App\Document\Comment;
use App\Entity\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs as EventLifecycleEventArgs;


class MyEventSubscriber
{
    public function __construct(
        private readonly DocumentManager $dm,
    ) {}

    public function postLoad(EventLifecycleEventArgs $eventArgs): void
    {
        $comment = $eventArgs->getDocument();

        if (!$comment instanceof Comment) {
            return;
        }

        $user = $this->dm->getReference(User::class, $comment->getUserId());

        $eventArgs->getObjectManager()
            ->getClassMetadata(Comment::class)
            ->reflClass
            ->getProperty('product')
            ->setValue($comment, $user);
    }
}
