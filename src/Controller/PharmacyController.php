<?php

namespace App\Controller;

use App\Entity\Pharmacy;
use App\Form\PharmacyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PharmacyController extends AbstractController
{
    /**
     * @Route("/pharmacy", name="pharmacy")
     */
    public function index(): Response
    {
        return $this->render('pharmacy/index.html.twig', [
            'controller_name' => 'PharmacyController',
        ]);
    }

    /**
     * @Route("/AddPharmacy", name="AddPharmacy")
     */
    public function addPharmacy(Request $request): Response
    {
        $pharmacy = new Pharmacy();

        $form = $this->createForm(PharmacyType::class,$pharmacy);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pharmacy);
            $em->flush();
            return $this->redirectToRoute('DisplayPharmacy');
        }

        return $this->render('pharmacy/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/DisplayPharmacy", name="DisplayPharmacy")
     */
    public function displayPharmacy(): Response
    {
        $pharmacy = $this->getDoctrine()->getManager()->getRepository(Pharmacy::class)->findAll();


        return $this->render('pharmacy/display.html.twig', [
            'pharmacies'=>$pharmacy]);

    }

    /**
     * @Route("DeletePharmacy/{id}",name="DeletePharmacy")
     */
    function DeletePharmacy($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Pharmacy::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('DisplayPharmacy');

    }

    /**
     * @Route("modiferpharmacy/{id}",name="modiferpharmacy")
     */
    function modifier ($id,Request $request)
    {
        $pharmacy=$this->getDoctrine()->getManager()->getRepository(Pharmacy::class)->find($id);
        $form=$this->createForm(PharmacyType::class,$pharmacy);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("DisplayPharmacy");
        }
        return $this->render("pharmacy/modifier.html.twig",['f'=>$form->createView()]);
    }





}
