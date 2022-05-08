<?php

namespace App\Controller;

use App\Entity\Compagnieaerienne;
use App\Entity\Hospital;
use App\Form\CompagnieaerienneType;
use App\Repository\CompagnieaerienneRepository;
use App\Repository\VolRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function addCompagnie(Request $request,FlashyNotifier $flashy): Response
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
                $flashy->success('Company created!');
                return $this->redirectToRoute('DisplayCompagnie');
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($compagnie);
                $em->flush();
                $flashy->success('Company created!');
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
    function DeleteCompagnie($id,FlashyNotifier $flashy)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Compagnieaerienne::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        $flashy->error("Company supprime");
        return $this->redirectToRoute('DisplayCompagnie');

    }

    /**
     * @Route("modiferCompagnie/{id}",name="modiferCompagnie")
     */
    function modifier ($id,Request $request,FlashyNotifier $flashy)
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
                $flashy->warning("Company Modified successfully");

                return $this->redirectToRoute("DisplayCompagnie");
            }
            else{
                $compagnie->setLogo(" ");
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $flashy->warning("Company Modified successfully");

                return $this->redirectToRoute("DisplayCompagnie");

            }
            }
        return $this->render("compagnieaerienne/modifier.html.twig",['f'=>$form->createView()]);
    }

    /**
     * @Route("/statCompagnie", name="statCompagnie")
     */
    public function statistiques(VolRepository $VolRepo){
        // On va chercher toutes les catégories
        $Compagnies_id = $VolRepo->CountVolid();
        $compagnies = $this->getDoctrine()->getManager()->getRepository(Compagnieaerienne::class)->findAll();

        $Compagniename = [];
        $numofCompagnies = [];
        $listVerif= [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($Compagnies_id as $Compagnie_id){
            foreach($compagnies as $compagnie){
                if($Compagnie_id[2]==$compagnie->getId()){
                    $Compagniename[] = $compagnie->getNom();
                    $numofCompagnies[] = $Compagnie_id[1];
                    $listVerif[]=$compagnie->getId();
                }
            }
        }
        if(count($Compagnies_id)<count($compagnies)){
            foreach($compagnies as $compagnie){
                if(!in_array($compagnie->getId(), $listVerif)){
                    $Compagniename[] = $compagnie->getNom();
                    $numofCompagnies[] = 0;
                }

            }
        }
        return $this->render('compagnieaerienne/stats.html.twig', [
            'Compagniename' => json_encode($Compagniename),
            'numofCompagnies' => json_encode($numofCompagnies),

        ]);
    }






}
