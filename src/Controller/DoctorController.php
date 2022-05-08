<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\RateDoctor;
use App\Entity\User;
use App\Form\DoctorType;
use App\Form\RateDoctorType;
use App\Repository\DoctorRepository;
use App\Repository\RateDoctorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio;

class DoctorController extends AbstractController
{
    /**
     * @Route("/doctor", name="doctor")
     */
    public function index(): Response
    {
        return $this->render('doctor/index.html.twig', [
            'controller_name' => 'DoctorController',
        ]);
    }

    /**
     * @Route("/AddDoctor", name="AddDoctor")
     */
    public function AddDoctor(Request $request): Response
    {
        $doctor = new Doctor();

        $form = $this->createForm(DoctorType::class, $doctor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Image = $form->get('Image')->getData();

            if ($Image) {
                $newFilename = uniqid() . '.' . $Image->guessExtension();
                try {
                    $Image->move(
                        $this->getParameter("images_directory"),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $doctor->setImage($newFilename);
                $em = $this->getDoctrine()->getManager();
                $em->persist($doctor);
                $em->flush();
                return $this->redirectToRoute('DisplayDoctor');
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->persist($doctor);
                $em->flush();
                return $this->redirectToRoute('DisplayDoctor');
            }

        }
        return $this->render('doctor/ajouter.html.twig', [
            'f' => $form->createView()]);
    }

    /**
     * @Route("/DisplayDoctor", name="DisplayDoctor")
     */
    public function displayDoctors(): Response
    {
        $doctors = $this->getDoctrine()->getManager()->getRepository(Doctor::class)->findAll();


        return $this->render('doctor/display.html.twig', [
            'doctors'=>$doctors]);

    }


    /**
     * @Route("/showDoctor/{id}", name="showDoctor")
     */
    public function displayDoctor($id,Request $request): Response
    {
        $rate = new RateDoctor();
        $rate = $this->getDoctrine()->getManager()->getRepository(RateDoctor::class)->findOneBy(['id_Doctor'=>$id,'id_user'=>1]);
        $doctors = $this->getDoctrine()->getManager()->getRepository(Doctor::class)->find($id);
        if($rate==null){
            $rate = new RateDoctor();
            $rate->setRate(0);
        }
        $form = $this->createForm(RateDoctorType::class, $rate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
            $rate->setIdUser($user);
            $rate->setIdDoctor($doctors);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rate);
            $em->flush();
            $client = new Twilio\Rest\Client('AC6f15ff6996e9b275c9ffb80f38aad3f4', 'a299126b8eb9775e7443eef50e52dbc9');
            $message = $client->messages->create(
            //'+216'.$user1->getNum(),
                '+21623110748',
                [
                    'from' => '+14123079450', // From a valid Twilio number
                    'body' => 'Salut,  Votre avez un nouveau Rate ',
                ]
            );
            return $this->redirectToRoute('showDoctor',['id'=>$id]);
        }

        return $this->render('doctor/ShowDoctor.html.twig', [
            'doctor'=>$doctors,'form1' => $form->createView(),'ratings'=>$rate->getRate()]);

    }


    /**
     * @Route("/DisplayDoctorUser", name="DisplayDoctorUser")
     */
    public function displayDoctorsUser(): Response
    {

        $doctors = $this->getDoctrine()->getManager()->getRepository(Doctor::class)->findAll();
        $rating =$this->getDoctrine()->getManager()->getRepository(RateDoctor::class)->findAll();

        return $this->render('doctor/displayUser.html.twig', [
            'doctors'=>$doctors,'rates'=>$rating,'id_User'=>1,]);

    }

    /**
     * @Route("deletedoctor/{id}",name="deletedoctor")
     */
    function DeleteDoctor($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Doctor::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('DisplayDoctor');

    }

    /**
     * @Route("modiferdoctor/{id}",name="modiferdoctor")
     */
    function modifierDoctor($id,Request $request)
    {
        $doctor=$this->getDoctrine()->getManager()->getRepository(Doctor::class)->find($id);
        $form=$this->createForm(DoctorType::class,$doctor);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {$Image = $form->get('Image')->getData();

            if ($Image) {
                $newFilename = uniqid() . '.' . $Image->guessExtension();
                try {
                    $Image->move(
                        $this->getParameter("images_directory"),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $doctor->setImage($newFilename);
                $em=$this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("DisplayDoctor");
            }else{
                $em=$this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("DisplayDoctor");
            }

        }
        return $this->render("doctor/modifier.html.twig",['f'=>$form->createView()]);
    }

    /**
     * @Route("/searchDoctorajax", name="ajaxDoctor")
     */
    public function searchajax(Request $request ,DoctorRepository $PartRepository)
    {
        $requestString=$request->get('searchValue');
        $jeux = $PartRepository->findDoctor($requestString);
        $rating =$this->getDoctrine()->getManager()->getRepository(RateDoctor::class)->findAll();

        return $this->render('ajax.html.twig', [
            "doctors"=>$jeux,
            'rates'=>$rating,'id_User'=>1,
        ]);
    }

    /**
     * @Route("/rate", name="rate", methods={"GET","POST"})
     */
    public function rate( Request $request,RateDoctorRepository $em){
        $iduser=$request->get('id_user');
        $iddoct=$request->get('idCours');
        $rate=$request->get('rating');
        $idrate=$request->get('idrate');
        $rating=$em->findOneBy(array('id_Doctor'=>$iddoct,'id_user'=>$iduser));
        $Rating=new RateDoctor();
        $entityManager = $this->getDoctrine()->getManagerForClass(RateDoctor::class);
        if($rating){
            $rating->setRate($rate);
            $client = new Twilio\Rest\Client('AC6f15ff6996e9b275c9ffb80f38aad3f4', 'a299126b8eb9775e7443eef50e52dbc9');
            $message = $client->messages->create(
                //'+216'.$user1->getNum(),
                '+21623110748',
                [
                    'from' => '+12034086653', // From a valid Twilio number
                    'body' => 'Salut,  Votre avez un nouveau Rate '.$rate,
                ]
            );
            $entityManager->flush();

        }else{
            $client = new Twilio\Rest\Client('AC6f15ff6996e9b275c9ffb80f38aad3f4', 'a299126b8eb9775e7443eef50e52dbc9');
            $message = $client->messages->create(
            //'+216'.$user1->getNum(),
                '+21623110748',
                [
                    'from' => '++14123079450', // From a valid Twilio number
                    'body' => 'Salut,  Votre avez un nouveau Rate ',
                ]
            );
            $Rating->setRate($rate);
            $Rating->setIdDoctor($iddoct);
            $Rating->setIdUser($iduser);
            $entityManager->persist($Rating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('DisplayDoctorUser');
    }
}





