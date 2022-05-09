<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\EvenementRepository;
use App\Services\QrcodeService;
use Dompdf\Dompdf;
use Dompdf\Options;
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
     * @Route("/AjouterReservation/{id_event}/{iduser}", name="AjouterReservation")
     */
    public function addreservation($id_event,$iduser,QrcodeService $qrcodeService): Response
    {
        $qrcode_txt=$id_event."/".$iduser;
        $reservation= new Reservation();
        #statique jusqu'a l'integration (user)
        $user= $this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
        $event=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id_event);
        $reservation->setIdUser($user);
        $reservation->setIdEvenement($event);
        $em=$this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();
        $qrcode=$qrcodeService->qrcodes($qrcode_txt);
        return $this->render('reservation/reservation.html.twig',['qrcode'=>$qrcode,'event'=>$event,'user'=>$user]);

    }

    /**
     * @Route("/imprimer_reservation/{QrCode}", name="imprimer_reservation")
     */

    public function imprimeReservation($QrCode): Response

    {
        $pdfOptions = new Options();
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);
        $pdfOptions->set('defaultFont', 'Arial');


        $dompdf = new Dompdf($pdfOptions);



        $dompdf->set_option("chroot", [__DIR__]);

        $image='<img src="C:\Users\Toumi\Desktop\Esprit\Symfony\Chaima\Pi\public\QrCode\img-qrcode626573ffe510f.png" >';
        $dompdf->loadHtml($image);

        $dompdf->setPaper('A2', 'portrait');

        $dompdf->render();

        $dompdf->stream("Reservation Qr.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('imprimer_reservation');

    }


    /**
     * @Route("/ShowReservation", name="ShowReservation")
     */
    public function showEvenementuser(): Response
    {
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($this->getUser()->getUsername());
        $reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>$user]);


        return $this->render('reservation/show.html.twig', [
            'user'=>$user,'reservations'=>$reservation]);

    }
    /**
     * @Route("/Verif/{id_event}/{iduser}", name="Verif")
     */
    public function Verif($id_event,$iduser): Response
    {
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
        $event=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id_event);
        $reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findOneBy(['id_user'=>$user,'id_evenement'=>$event]);
        if($reservation!=null){
            $result=true;
        }else{
            $result=false;
        }

        return $this->render('reservation/verif.html.twig', [
            'user'=>$user,'result'=>$result]);

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
