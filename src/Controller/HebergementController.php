<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HebergementController extends AbstractController
{
    /**
     * @Route("/hebergement", name="hebergement")
     */
    public function index(HebergementRepository $repository): Response
    {
        return $this->render('hebergement/index.html.twig', [
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hebergement);
            $entityManager->flush();

            return $this->redirectToRoute('hebergement');
        }

        return $this->render('hebergement/add.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/hebergement/{id}", name="hebergementShow")
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


        return $this->redirectToRoute('hebergement');
    }
}
