<?php

namespace App\Controller;

use App\Entity\Compagnieaerienne;
use App\Form\CompagnieaerienneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompagnieaerienneController extends AbstractController
{
    /**
     * @Route("/compagnieaerienne", name="compagnieaerienne")
     */
    public function index(): Response
    {
        return $this->render('compagnieaerienne/index.html.twig', [
            'controller_name' => 'CompagnieaerienneController',
        ]);
    }

    /**
     * @Route("/AddCompagnie", name="AddCompagnie")
     */
    public function addCompagnie(Request $request): Response
    {
        $compagnie = new Compagnieaerienne();

        $form = $this->createForm(CompagnieaerienneType::class,$compagnie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $Logo = $form->get('Logo')->getData();

            if ($Logo) {
                $newFilename = uniqid() . '.' . $Logo->guessExtension();
                try {
                    $Logo->move(
                        $this->getParameter("images_directory"),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $compagnie->setLogo($newFilename);
                $em = $this->getDoctrine()->getManager();
                $em->persist($compagnie);
                $em->flush();
                return $this->redirectToRoute('DisplayCompagnie');
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($compagnie);
                $em->flush();
                return $this->redirectToRoute('DisplayCompagnie');
            }
        }


        return $this->render('compagnieaerienne/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/DisplayCompagnie", name="DisplayCompagnie")
     */
    public function displayHospital(): Response
    {
        $compagnies = $this->getDoctrine()->getManager()->getRepository(Compagnieaerienne::class)->findAll();


        return $this->render('compagnieaerienne/display.html.twig', [
            'Compagnies'=>$compagnies]);

    }

    /**
     * @Route("deleteCompagnie/{id}",name="deleteCompagnie")
     */
    function DeleteCompagnie($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Compagnieaerienne::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('DisplayCompagnie');

    }

    /**
     * @Route("modiferCompagnie/{id}",name="modiferCompagnie")
     */
    function modifier ($id,Request $request)
    {
        $compagnie=$this->getDoctrine()->getManager()->getRepository(Compagnieaerienne::class)->find($id);
        $form=$this->createForm(CompagnieaerienneType::class,$compagnie);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $Logo = $form->get('Logo')->getData();

            if ($Logo) {
                $newFilename = uniqid() . '.' . $Logo->guessExtension();
                try {
                    $Logo->move(
                        $this->getParameter("images_directory"),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $compagnie->setLogo($newFilename);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("DisplayCompagnie");
            }
            else{
                $compagnie->setLogo(" ");
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("DisplayCompagnie");

            }
            }
        return $this->render("compagnieaerienne/modifier.html.twig",['f'=>$form->createView()]);
    }






}
