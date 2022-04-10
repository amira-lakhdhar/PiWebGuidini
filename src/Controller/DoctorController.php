<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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




}
