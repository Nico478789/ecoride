<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\RideType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RideController extends AbstractController
{
    #[Route('/ride/create', name: 'app_ride_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $ride = new Ride();
        $user = $this->getUser();

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

    // List all rides
    #[Route('/ride', name: 'app_ride_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em,): Response
    {
        // $rides = $em->getRepository(Ride::class)->findAll();
        $query = $em->createQuery('SELECT r.id, r.whereTo, r.whereFrom, r.departure_time, u.nickname, c.brand_name FROM App\Entity\Ride r JOIN r.car c JOIN c.driver u');
        $rides = $query->getResult();

        return $this->render('ride/list.html.twig', [
            'rides' => $rides,
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
    public function arrive(Ride $ride, EntityManagerInterface $em): Response
    {
        $ride->setStatus('arrived');
        $em->flush();

        return $this->redirectToRoute('me');
    }
}
