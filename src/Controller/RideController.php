<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Form\RideType;
use App\Entity\User;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class RideController extends AbstractController
{
    #[Route('/ride/create', name: 'app_ride_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $ride = new Ride();

        $ride->setStatus('created');

        $form = $this->createForm(RideType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ride);
            $em->flush();

            return $this->redirectToRoute('me');
        }

        return $this->render('ride/create.html.twig', [
            'formview' => $form->createView(),
        ]);
    }

    #[Route('/ride/{id}', name: 'app_ride_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Ride $ride): Response
    {
        $seats = $ride->getNumberOfSeats();
        $passengers = $ride->getPassenger();
        $passengerCount = count($passengers);
        $availableSeats = $seats - $passengerCount;
        return $this->render('ride/ride.html.twig', [
            'ride' => $ride,
            'availableSeats' => $availableSeats,
        ]);
    }

    #[Route('/ride/{id}/edit', name: 'app_ride_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Ride $ride, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RideType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_ride_list');
        }

        return $this->render('ride/edit.html.twig', [
            'formview' => $form->createView(),
        ]);
    }

    #[Route('/ride/{id}/delete', name: 'app_ride_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(EntityManagerInterface $em, Ride $ride): Response
    {
        $em->remove($ride);
        $em->flush();

        return $this->redirectToRoute('app_ride_list');
    }

    // List all rides with filtering options
    #[Route('/ride', name: 'app_ride_list', methods: ['GET', 'POST'])]
    public function list(Request $request, Ride $ride, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SearchType::class, $ride);
        $form->handleRequest($request);
        $departureCity = $request->get('departureCity');
        $arrivalCity = $request->get('arrivalCity');
        $departureDate = $request->get('departureDate');

        // If the form FROM THIS PAGE is submitted and valid, filter the rides based on the form data
        if ($form->isSubmitted() && $form->isValid()) {

            $departureCity = $form->get('whereFrom')->getData();
            $arrivalCity = $form->get('whereTo')->getData();
            $departureDate = $form->get('DepartureTime')->getData();

            $query = $em->createQueryBuilder()
                ->select('r.id, r.whereTo, r.whereFrom, r.departure_time, u.nickname, c.brand_name')
                ->from(Ride::class, 'r')
                ->join('r.car', 'c')
                ->join('c.driver', 'u')
                ->where('r.whereFrom = :departureCity')
                ->setParameter('departureCity', $departureCity)
                ->andWhere('r.whereTo = :arrivalCity')
                ->setParameter('arrivalCity', $arrivalCity)
                ->andWhere('r.departure_time >= :departureDate')
                ->setParameter('departureDate', $departureDate)
                ->getQuery()
                ->getResult();

            return $this->render('ride/list.html.twig', [
                'formview' => $form->createView(),
                'rides' => $query,
            ]);
        }

        // If the information comes from home page : filter applied
        if (isset($departureCity) && isset($arrivalCity) && isset($departureDate)) {
            $query = $em->createQueryBuilder()
                ->select('r.id, r.whereTo, r.whereFrom, r.departure_time, u.nickname, c.brand_name')
                ->from(Ride::class, 'r')
                ->join('r.car', 'c')
                ->join('c.driver', 'u')
                ->where('r.whereFrom = :departureCity')
                ->setParameter('departureCity', $departureCity)
                ->andWhere('r.whereTo = :arrivalCity')
                ->setParameter('arrivalCity', $arrivalCity)
                ->andWhere('r.departure_time >= :departureDate')
                ->setParameter('departureDate', $departureDate)
                ->getQuery()
                ->getResult();

            // If no filter is applied, get all rides
            // Using DQL to fetch all rides with their associated car and driver
        } else {
            $query = $em->createQuery('SELECT r.id, r.whereTo, r.whereFrom, r.departure_time, u.nickname, c.brand_name FROM App\Entity\Ride r JOIN r.car c JOIN c.driver u');
            $query = $query->getResult();
        }

        return $this->render('ride/list.html.twig', [
            'formview' => $form->createView(),
            'rides' => $query,
        ]);
    }

    #[Route('/ride/{id}/join', name: 'app_ride_join', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function join(Ride $ride, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($user) {
            $ride->addPassenger($user);
            $em->flush();
        }

        return $this->redirectToRoute('me');
    }

    #[Route('/ride/{id}/go', name: 'app_ride_go', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function leave(Ride $ride, EntityManagerInterface $em): Response
    {
        $ride->setStatus('on the way');
        $em->flush();

        return $this->redirectToRoute('me');
    }

    #[Route('/ride/{id}/arrived', name: 'app_ride_arrived', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function arrive(Ride $ride, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $ride->setStatus('arrived');
        $em->flush();
        $passengers = $ride->getPassenger();
        foreach ($passengers as $passenger) {
            $user = $em->getRepository(User::class)->find($passenger->getId());
            $email = (new TemplatedEmail())
                ->from('rides@ecoride.com')
                ->to($user->getEmail())
                ->subject('Vous êtes arrivé à destination')
                ->htmlTemplate('mailing/arrived.html.twig')
                ->context([
                    'ride' => $ride,
                    'user' => $user,
                ]);


            $mailer->send($email);
        }


        return $this->redirectToRoute('me');
    }
    #[Route('/ride/{id}/done', name: 'app_ride_done', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function confirm(Ride $ride, EntityManagerInterface $em): Response
    {
        $ride->setStatus('done');
        $em->flush();

        return $this->redirectToRoute('me');
    }
}
