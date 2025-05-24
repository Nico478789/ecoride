<?php

namespace App\Document;

use App\Entity\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


#[MongoDB\Document]
class Comment
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private $title;

    #[MongoDB\Field(type: 'string')]
    private $content;

    // This is a string field that will store the user's nickname as userId
    #[MongoDB\Field(type: 'string')]
    private ?string $userId;
    private ?User $author = null;

    public function getUserId(): ?string
    {
        return $this->userId;
    }
    public function setAuthor(User $author): void
    {
        $this->userId = $author->getNickname();
        $this->author = $author;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    // This is a string field that will store the nickname of the user targeted by the comment
    #[MongoDB\Field(type: 'string')]
    private ?string $targetId;
    private ?User $target = null;

    public function getTargetId(): ?string
    {
        return $this->targetId;
    }
    public function setTarget(User $target): void
    {
        $this->targetId = $target->getNickname();
        $this->target = $target;
    }

    public function getTarget(): ?User
    {
        return $this->target;
    }


    public function settitle(string $title): void
    {
        $this->title = $title;
    }
    public function gettitle(): string
    {
        return $this->title;
    }
    public function setcontent(string $content): void
    {
        $this->content = $content;
    }
    public function getcontent(): string
    {
        return $this->content;
    }
    public function getId(): string
    {
        return $this->id;
    }
}
