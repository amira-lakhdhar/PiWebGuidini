<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Entity\Reservation;
use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/hbrg",name="dsd")
 */
class HebergementController extends AbstractController
{
    /**
     * @Route("/hebergement", name="hebergementyy")
     */
    public function index(HebergementRepository $repository,ReservationRepository $reservationRepository): Response
    {   $hebergements=$repository->findAll();
        $reservations=$reservationRepository->findAll();
        foreach ($reservations as $reservation) {
            foreach ($hebergements as $hebergement) {
                if ($hebergement->getId() == $reservation->getHebergement()->getId()) {
                    $hebergement->setIsReserved(1);
                    $hebergement->setReservation($reservation);
                }
            }
        }
        return $this->render('hebergement/index.html.twig', [
            'hebergement' => $hebergements,
            'reservation' => $reservations
        ]);
    }

    /**
     * @Route("/hebergements", name="sorted")
     */
    public function sorted(HebergementRepository $repository,ReservationRepository $reservationRepository ): Response
    {

        $hebergements=$repository->sort();
        $reservations=$reservationRepository->findAll();
        foreach ($reservations as $reservation) {
            foreach ((array)$hebergements as $hebergement) {
                if ($hebergement->getId() == $reservation->getHebergement()->getId()) {
                    $hebergement->setIsReserved(1);
                    $hebergement->setReservation($reservation);
                }
            }
        }
        return $this->render('hebergement/indexs.html.twig', [
            'hebergement' => $hebergements,
            'reservation' => $reservations
        ]);
    }

    /**
     * @Route("/hebergementAdmin", name="hebergementyyAdmin")
     */
    public function indexAdmin(HebergementRepository $repository): Response
    {

        return $this->render('hebergement/indexAdmin.html.twig', [
            'hebergement' => $repository->findAll(),
        ]);
    }
    /**
     * @Route ("/hebergement/Add", name="AddHebergement")
     */
    public function add(Request $request): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $image=$form->get('Photo')->getData();
                $fichier=md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
            }catch (FileException $e){

            }

            $hebergement->setPhoto($fichier);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hebergement);
            $entityManager->flush();

            return $this->redirectToRoute('dsdhebergementyyAdmin');
        }

        return $this->render('hebergement/add.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/Show", name="hebergementShowtr")
     */
    public function show(Hebergement $hebergement): Response
    {
        return $this->render('hebergement/show.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hebergementEdit")
     */
    public function edit(Request $request, Hebergement $hebergement): Response
    {
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hebergement');
        }

        return $this->render('hebergement/edit.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="hebergementDelete")
     */
    public function delete(Request $request, Hebergement $hebergement): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($hebergement);
        $entityManager->flush();


        return $this->redirectToRoute('dsdhebergementyyAdmin');
    }
}
