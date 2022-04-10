<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\User;
use App\Form\AvisType;
use App\Form\UserType;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="avis")
     */
    public function index(AvisRepository $repository): Response
    {
        return $this->render('avis/index.html.twig', [
            'avis' => $repository->findAll(),
        ]);
    }

    /**
     * @Route ("/avis/Add", name="AddAvis")
     */
    public function add(Request $request): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('avis');
        }

        return $this->render('avis/add.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/avis/{id}", name="avisShow")
     */
    public function show(Avis $avis): Response
    {
        return $this->render('user/show.html.twig', [
            'avis' => $avis,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avisEdit")
     */
    public function edit(Request $request, Avis $avis): Response
    {
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avis');
        }

        return $this->render('avis/edit.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avisDelete")
     */
    public function delete(Request $request, Avis $avis): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($avis);
        $entityManager->flush();


        return $this->redirectToRoute('avis');
    }
}
