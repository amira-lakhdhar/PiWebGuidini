<?php

namespace App\Controller;

use App\Entity\Vol;
use App\Form\VolType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VolController extends AbstractController
{
    /**
     * @Route("/vol", name="vol")
     */
    public function index(): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }


    /**
     * @Route("/AddVol", name="AddVol")
     */
    public function addVol(Request $request): Response
    {
        $vol = new Vol();

        $form = $this->createForm(VolType::class,$vol);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vol);
            $em->flush();
            return $this->redirectToRoute('ConsulterVol');
        }

        return $this->render('vol/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/ConsulterVol", name="ConsulterVol")
     */
    public function displayVols(): Response
    {
        $vols = $this->getDoctrine()->getManager()->getRepository(Vol::class)->findAll();


        return $this->render('vol/display.html.twig', [
            'vols'=>$vols]);

    }

    /**
     * @Route("DeleteVol/{id}",name="DeleteVol")
     */
    function DeleteVol($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Vol::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('ConsulterVol');

    }

    /**
     * @Route("modifierVol/{id}",name="modifierVol")
     */
    function modifier ($id,Request $request)
    {
        $vol=$this->getDoctrine()->getManager()->getRepository(Vol::class)->find($id);
        $form=$this->createForm(VolType::class,$vol);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("ConsulterVol");
        }
        return $this->render("vol/modifier.html.twig",['f'=>$form->createView()]);
    }






}
