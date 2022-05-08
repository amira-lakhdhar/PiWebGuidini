<?php

namespace App\Controller;

use App\Entity\BackTest;
use App\Form\BackTestType;
use App\Repository\BackTestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/test")
 */
class BackTestController extends AbstractController
{
    /**
     * @Route("/", name="back_test_index", methods={"GET"})
     */
    public function index(BackTestRepository $backTestRepository): Response
    {
        return $this->render('back_test/index.html.twig', [
            'back_tests' => $backTestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_test_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $backTest = new BackTest();
        $form = $this->createForm(BackTestType::class, $backTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($backTest);
            $entityManager->flush();

            return $this->redirectToRoute('back_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_test/new.html.twig', [
            'back_test' => $backTest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="back_test_show", methods={"GET"})
     */
    public function show(BackTest $backTest): Response
    {
        return $this->render('back_test/show.html.twig', [
            'back_test' => $backTest,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_test_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BackTest $backTest): Response
    {
        $form = $this->createForm(BackTestType::class, $backTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_test/edit.html.twig', [
            'back_test' => $backTest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="back_test_delete", methods={"POST"})
     */
    public function delete(Request $request, BackTest $backTest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$backTest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($backTest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_test_index', [], Response::HTTP_SEE_OTHER);
    }
}
