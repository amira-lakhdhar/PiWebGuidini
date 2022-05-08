<?php

namespace App\Controller;

use App\Entity\Hospital;
use App\Form\HospitalType;
use App\Repository\DoctorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HospitalController extends AbstractController
{
    /**
     * @Route("/hospital", name="display")
     */
    public function index(): Response
    {
        return $this->render('hospital/index.html.twig', [
            'controller_name' => 'HospitalController',
        ]);
    }

    /**
     * @Route("/AddHospital", name="AddHospital")
     */
    public function addHospital(Request $request): Response
    {
        $hospital = new Hospital();

        $form = $this->createForm(HospitalType::class,$hospital);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hospital);
            $em->flush();
            return $this->redirectToRoute('DisplayHospital');
        }

        return $this->render('hospital/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/DisplayHospital", name="DisplayHospital")
     */
    public function displayHospital(): Response
    {
        $hospital = $this->getDoctrine()->getManager()->getRepository(Hospital::class)->findAll();


        return $this->render('hospital/display.html.twig', [
            'hospitals'=>$hospital]);

    }

    /**
     * @Route("deletehospital/{id}",name="deletehospital")
     */
    function Delete ($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Hospital::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('DisplayHospital');

    }

    /**
     * @Route("modiferhospital/{id}",name="modiferhospital")
     */
    function modifier ($id,Request $request)
    {
        $hospital=$this->getDoctrine()->getManager()->getRepository(Hospital::class)->find($id);
        $form=$this->createForm(HospitalType::class,$hospital);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("DisplayHospital");
        }
        return $this->render("hospital/modifier.html.twig",['f'=>$form->createView()]);
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(DoctorRepository $DocRepo){
        // On va chercher toutes les catégories
        $hospitals_id = $DocRepo->CountHosptalId();
        $hospitals = $this->getDoctrine()->getManager()->getRepository(Hospital::class)->findAll();

        $HospitalName = [];
        $categColor = [];
        $NumofDoctorsCount = [];
        $listVerif= [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($hospitals_id as $hospital_id){
            foreach($hospitals as $hospital){
            if($hospital_id[2]==$hospital->getId()){
                $HospitalName[] = $hospital->getName();
                $NumofDoctorsCount[] = $hospital_id[1];
                $listVerif[]=$hospital->getId();
            }
            }
        }
        if(count($hospitals_id)<count($hospitals)){
                foreach($hospitals as $hospital){
                    if(!in_array($hospital->getId(), $listVerif)){
                        $HospitalName[] = $hospital->getName();
                        $NumofDoctorsCount[] = 0;
                    }

            }
        }
        return $this->render('hospital/stats.html.twig', [
            'HospitalName' => json_encode($HospitalName),
            'NumofDoctorsCount' => json_encode($NumofDoctorsCount),

        ]);
    }








    }
