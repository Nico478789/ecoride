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
        if ($user) {
            $ride->addPassenger($user);
        }
        $form = $this->createForm(RideType::class, $ride);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ride);
            $em->flush();

            return $this->redirectToRoute('app_ride_list');
        }

        return $this->render('ride/create.html.twig', [
            'formview' => $form->createView(),
        ]);
    }

    #[Route('/ride/{id}/join', name: 'app_ride_join', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function join(Ride $ride, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($user) {
            $ride->addPassenger($user);
            $em->flush();
        }

        return $this->redirectToRoute('app_ride_list');
    }

    #[Route('/ride/{id}', name: 'app_ride_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Ride $ride): Response
    {
        return $this->render('ride/ride.html.twig', [
            'ride' => $ride,
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

    #[Route('/ride', name: 'app_ride_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em,): Response
    {
        // $rides = $em->getRepository(Ride::class)->findAll();
        $query = $em->createQuery('SELECT r.id, r.whereTo, r.whereFrom, u.email FROM App\Entity\Ride r JOIN r.passenger u');
        $rides = $query->getResult();

        return $this->render('ride/list.html.twig', [
            'rides' => $rides,
        ]);
    }
}
