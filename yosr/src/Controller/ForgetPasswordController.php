<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgetPasswordType;
use App\Form\UserType;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForgetPasswordController extends AbstractController
{
    public function generateRandomString($length = 16, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'):String
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @Route("/forget/password", name="app_forget_password")
     */
    public function index(Request $request, Swift_Mailer $mailer,UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ForgetPasswordType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $user=new User();
        $user = $em->getRepository(User::class)->findOneByEmail($form["email"]->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            $message=(new \Swift_Message('Mot de passe oublié'));
            $message->setFrom("Guidini.pidev@gmail.com");
            $message->setTo($form["email"]->getData());
            $length = 12;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $message->setBody("Votre nouveau mot passe est : " .$randomString."  changez votre mot de passe dés que vous connectez");
            $hash = $encoder->encodePassword($user,$randomString);
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            try{
                $response = $mailer->send($message);
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
                dd($response);
            }
            $this->addFlash('message','le message a ete envoie');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('forget_password/index.html.twig', [

            'form' => $form->createView(),
        ]);
    }

}
