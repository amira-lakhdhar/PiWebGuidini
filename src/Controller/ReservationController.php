<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\User;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @Route("/AjouterReservation/{id_event}", name="AjouterReservation")
     */
    public function addreservation($id_event): Response
    {
        $reservation= new Reservation();
        #statique jusqu'a l'integration (user)
        $user= $this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $event=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id_event);
        $reservation->setIdUser($user);
        $reservation->setIdEvenement($event);
        $em=$this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();
        return $this->redirectToRoute('ShowEvenementUser');

    }


    /**
     * @Route("/ShowReservation", name="ShowReservation")
     */
    public function showEvenementuser(): Response
    {
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>$user]);


        return $this->render('reservation/show.html.twig', [
            'user'=>$user,'reservations'=>$reservation]);

    }


    /**
     * @Route("deletereservation/{id}",name="deletereservation")
     */
    function Deletereservation($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('ShowReservation');

    }





}
