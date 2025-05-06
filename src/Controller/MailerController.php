<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('didierdeschamps@example.com')
            ->to('zinedine@example.com')
            ->subject('Coupe du monde')
            ->text('vous êtes invité à la prochaine coupe du monde');

        $mailer->send($email);

        // ...
        return new Response(
            'Email was sent'
        );
    }
}
