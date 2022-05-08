<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Form\UserType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReclamationController
 * @package App\Controller
 * @Route("/rec", name="rec")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(ReclamationRepository $repository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamation' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/Mesreclamation/{id}", name="Mesreclamation")
     */
    public function MesIndex(User $user,ReclamationRepository $repository,UserRepository $userRepository): Response
    {
        return $this->render('reclamation/Mesindex.html.twig', [
            'reclamation' => $repository->findBy(['User'=>$user]),
        ]);
    }

    /**
     * @Route ("/reclamation/Add/{id}", name="AddReclamation")
     */
    public function add(User $user,Request $request,UserRepository $userRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $reclamation->setUser($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('recMesreclamation',['id'=>$user->getId()]);
        }

        return $this->render('reclamation/add.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reclamation/{id}", name="reclamationShow")
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/reclamationAdmin/{id}", name="reclamationShowAdmin")
     */
    public function showAdmin(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/Adminshow.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reclamationEdit")
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recMesreclamation',['id'=>$reclamation->getUser()->getId()]);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/del/{id}", name="reclamationDelete")
     */
    public function delete(Request $request, Reclamation $reclamation): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reclamation);
        $entityManager->flush();


        return $this->redirectToRoute('recMesreclamation',['id'=>$reclamation->getUser()->getId()]);
    }
}
