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
 * @Route("/rec")
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
<<<<<<< HEAD
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
=======
     * @Route ("/reclamation/Add", name="AddReclamation")
     */
    public function add(Request $request): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
>>>>>>> 884436c794d2793b01dfb6da78223d4abf31561c
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

<<<<<<< HEAD
            return $this->redirectToRoute('recMesreclamation',['id'=>$user->getId()]);
=======
            return $this->redirectToRoute('reclamation');
>>>>>>> 884436c794d2793b01dfb6da78223d4abf31561c
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
     * @Route("/{id}/edit", name="reclamationEdit")
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $form = $this->createForm(UserType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

<<<<<<< HEAD
            return $this->redirectToRoute('recMesreclamation',['id'=>$reclamation->getUser()->getId()]);
=======
            return $this->redirectToRoute('reclamation');
>>>>>>> 884436c794d2793b01dfb6da78223d4abf31561c
        }

        return $this->render('reclamation/edit.html.twig', [
            'user' => $reclamation,
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


<<<<<<< HEAD
        return $this->redirectToRoute('recMesreclamation',['id'=>$reclamation->getUser()->getId()]);
    }

    /**
     * @Route("/searchReclamationajax", name="ajaxReclamation")
     */
    public function searchajax(Request $request ,ReclamationRepository $PartRepository)
    {
        $requestString=$request->get('searchValue');
        $jeux = $PartRepository->findReclamationAjax($requestString);

        return $this->render('reclamation/ajax.html.twig', [
            "reclamation"=>$jeux,
        ]);
=======
        return $this->redirectToRoute('reclamation');
>>>>>>> 884436c794d2793b01dfb6da78223d4abf31561c
    }
}
