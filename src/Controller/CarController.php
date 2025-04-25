<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarController extends AbstractController
{
    #[Route('/car/create', name: 'app_car_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $car = new Car();
        $user = $this->getUser();
        if ($user) {
            $car->setDriver($user);
        }
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($car);
            $em->flush();

            return $this->redirectToRoute('app_car_list');
        }

        return $this->render('car/create.html.twig', [
            'formview' => $form->createView(),
        ]);
    }

    #[Route('/car/{id}/edit', name: 'app_car_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Car $car, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_car_list');
        }

        return $this->render('car/edit.html.twig', [
            'formview' => $form->createView(),
        ]);
    }

    #[Route('/car/{id}/delete', name: 'app_car_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(EntityManagerInterface $em, Car $car): Response
    {
        $em->remove($car);
        $em->flush();

        return $this->redirectToRoute('app_car_list');
    }

    #[Route('/car', name: 'app_car_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): Response
    {
        $cars = $em->getRepository(Car::class)->findBy(['driver' => $this->getUser()]);

        return $this->render('car/list.html.twig', [
            'cars' => $cars,
        ]);
    }
}
