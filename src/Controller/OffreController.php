<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreController extends AbstractController
{
    /**
     * @Route("/offre", name="offre")
     */
    public function index(): Response
    {
        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

    /**
     * @Route("/AddOffre", name="AddOffre")
     */
    public function addOffre(Request $request): Response
    {
        $offre = new Offre();

        $form = $this->createForm(OffreType::class,$offre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute('ConsulterOffre');
        }

        return $this->render('offre/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/ConsulterOffre", name="ConsulterOffre")
     */
    public function displayOffre(): Response
    {
        $offers = $this->getDoctrine()->getManager()->getRepository(Offre::class)->findAll();


        return $this->render('offre/display.html.twig', [
            'offers'=>$offers]);

    }

    /**
     * @Route("DeleteOffre/{id}",name="DeleteOffre")
     */
    function DeleteOffre($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Offre::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('ConsulterOffre');

    }

    /**
     * @Route("modifierOffre/{id}",name="modifierOffre")
     */
    function modifierOffre($id,Request $request)
    {
        $vol=$this->getDoctrine()->getManager()->getRepository(Offre::class)->find($id);
        $form=$this->createForm(OffreType::class,$vol);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("ConsulterOffre");
        }
        return $this->render("offre/modifier.html.twig",['f'=>$form->createView()]);
    }




}
