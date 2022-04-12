<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoyageController extends AbstractController
{
    /**
     * @Route("/voyage", name="voyage")
     */
    public function index(): Response
    {
        return $this->render('voyage/index.html.twig', [
            'controller_name' => 'VoyageController',
        ]);
    }


    /**
     * @Route("/AddVoyage", name="AjouterVoyage")
     */
    public function addVoyage(Request $request): Response
    {
        $voyage = new Voyage();

        $form = $this->createForm(VoyageType::class,$voyage);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($voyage);
            $em->flush();
            return $this->redirectToRoute('ConsulterVoyage');
        }

        return $this->render('voyage/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/ConsulterVoyage", name="ConsulterVoyage")
     */
    public function displayVoyages(): Response
    {
        $voyages = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();


        return $this->render('voyage/display.html.twig', [
            'voyages'=>$voyages]);

    }

    /**
     * @Route("DeleteVoyage/{id}",name="DeleteVoyage")
     */
    function DeleteVoyage($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Voyage::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('ConsulterVoyage');

    }

    /**
     * @Route("ModifierVoyage/{id}",name="ModifierVoyage")
     */
    function ModifierVoyage($id,Request $request)
    {
        $voyage=$this->getDoctrine()->getManager()->getRepository(Voyage::class)->find($id);
        $form=$this->createForm(VoyageType::class,$voyage);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("ConsulterVoyage");
        }
        return $this->render("voyage/modifier.html.twig",['f'=>$form->createView()]);
    }






}
