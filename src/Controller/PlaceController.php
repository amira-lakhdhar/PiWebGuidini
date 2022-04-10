<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\User;
use App\Form\PlaceType;
use App\Form\UserType;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/place", name="place")
     */
    public function index(PlaceRepository $repository): Response
    {
        return $this->render('place/index.html.twig', [
            'place' => $repository->findAll(),
        ]);
    }

    /**
     * @Route ("/place/Add", name="AddPlace")
     */
    public function add(Request $request): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();

            return $this->redirectToRoute('place');
        }

        return $this->render('place/add.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/place/{id}", name="placeShow")
     */
    public function show(Place $place): Response
    {
        return $this->render('place/show.html.twig', [
            'place' => $place,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="placeEdit")
     */
    public function edit(Request $request, Place $place): Response
    {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('place');
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="placeDelete")
     */
    public function delete(Request $request, Place $place): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($place);
        $entityManager->flush();


        return $this->redirectToRoute('place');
    }
}
