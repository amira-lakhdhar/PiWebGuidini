<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="security_home")
     */
    public function  index(): Response
    {
        return $this->render('security/index.html.twig');
    }
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user=new User();
        $entityManager=$this->getDoctrine()->getManager();
        $form=$this->createForm(RegistrationFormType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $user->setRole('User');
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
            $entityManager->persist($user);
            $entityManager->flush();
            $this->redirectToRoute("security_home");
        }

        return $this->render('security/signUp.html.twig',[
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route("/login", name="security_login" , methods={"GET","POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function login(AuthenticationUtils $authenticationUtils){


        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername=$authenticationUtils->getLastUsername();

        if($error){
            $this->addFlash('login','Error Login');
        }

        if(TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute('security_home');
        }if(TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('recreclamation');
        }
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){
    }
}
