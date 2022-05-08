<?php

namespace App\Controller;

use App\Entity\FrontTest;
use App\Form\FrontTestType;
use App\Repository\FrontTestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/front/test")
 */
class FrontTestController extends AbstractController
{
    /**
     * @Route("/", name="front_test_index", methods={"GET"})
     */
    public function index(FrontTestRepository $frontTestRepository): Response
    {
        return $this->render('front_test/index.html.twig', [
            'front_tests' => $frontTestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="front_test_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $frontTest = new FrontTest();
        $form = $this->createForm(FrontTestType::class, $frontTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($frontTest);
            $entityManager->flush();

            return $this->redirectToRoute('front_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front_test/new.html.twig', [
            'front_test' => $frontTest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="front_test_show", methods={"GET"})
     */
    public function show(FrontTest $frontTest): Response
    {
        return $this->render('front_test/show.html.twig', [
            'front_test' => $frontTest,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="front_test_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FrontTest $frontTest): Response
    {
        $form = $this->createForm(FrontTestType::class, $frontTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('front_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front_test/edit.html.twig', [
            'front_test' => $frontTest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="front_test_delete", methods={"POST"})
     */
    public function delete(Request $request, FrontTest $frontTest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$frontTest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($frontTest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('front_test_index', [], Response::HTTP_SEE_OTHER);
    }
}
