<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request, Ride $ride): Response
    {

        $form = $this->createForm(SearchType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $departureCity = $form->get('whereFrom')->getData();
            $arrivalCity = $form->get('whereTo')->getData();
            $departureDate = $form->get('DepartureTime')->getData();

            return $this->redirectToRoute('app_ride_list', ['departureCity' => $departureCity, 'arrivalCity' => $arrivalCity, 'departureDate' => $departureDate->format('Y-m-d')]);
        }

        return $this->render('home/index.html.twig', [
            // 'controller_name' => 'HomeController',
            'formview' => $form->createView(),

        ]);
    }
}
