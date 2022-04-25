<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/usr", name="usr")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(UserRepository $repository): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $repository->findAll(),
        ]);
    }

    /**
     * @Route ("/user/Add", name="AddUser")
     */
    public function add(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('usruser');
        }

        return $this->render('user/add.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/user/{id}", name="userShow")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="userEdit")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usruser');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/del/{id}", name="userDelete")
     */
    public function delete(Request $request, User $user): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();


        return $this->redirectToRoute('usruser');
    }

    /**
     * @Route("/signUp", name="signUp")
     */
    public function signUp(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole("User");
            try {
                $image=$form->get('Photo')->getData();
                $fichier=md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
            }catch (FileException $e){

            }

            $user->setPhoto($fichier);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('usrsignIn');
        }

        return $this->render('user/signUp.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signIn", name="signIn")
     */
    public function signIn(Request $request,UserRepository $repository): Response
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user2=$repository->findOneBy(['email'=>$user->getEmail()]);
            $user->setNom($user2->getNom());
            $user->setAdresse($user2->getAdresse());
            $user->setPrenom($user2->getPrenom());
            $user->setRole($user2->getRole());
            if ($user2 && $user->getPassword()==$user2->getPassword()) {
                return $this->redirectToRoute('usruserEdit',['id'=>$user2->getId()]);

            }
        }
        return $this->render('user/signIn.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
