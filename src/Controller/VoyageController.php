<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\DoctorRepository;
use App\Repository\VoyageRepository;
use DateInterval;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoyageController extends AbstractController
{
//    /**
//     * @Route("/voyage", name="voyage")
//     */
//    public function index(): Response
//    {
//        return $this->render('voyage/index.html.twig', [
//            'controller_name' => 'VoyageController',
//        ]);
//    }

    /**
     * @Route("/voyagecalendar", name="voyagecalendar")
     */
    public function calendar(VoyageRepository $repository): Response
    {

        $rep = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();

        $rdvs = [];
        foreach ($rep as $event)
        {

            $rdvs[]=[
                'id' => $event->getId(),
                'title'=>$event->getVol()->getCompagnie()->getNom(),
                'start'=>$event->getVol()->getDateVol()->format('Y-m-d H:i:s'),
                'end'=>$event->getVol()->getDateVol()->add(new DateInterval('PT10H'))->format('Y-m-d H:i:s'),
                'description' => $event->getDescription(),
                'backgroundColor'=> '#dcdcdc',
                'borderColor'=> 'green',
                'textColor' => 'black'
            ];

        }


        $data = json_encode($rdvs);
        return $this->render('voyage/index.html.twig',compact('data' ));
    }

    /**
     * @Route("/voyagecalendar/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Voyage $voyage, Request $request,FlashyNotifier $flashy)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$voyage){
                // On instancie un rendez-vous
                $voyage = new Voyage();

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $voyage->getVol()->getCompagnie()->setNom($donnees->title);
            $voyage->setDescription($donnees->description);
            $voyage->getVol()->setDateVol(new \DateTime($donnees->start));

            $em = $this->getDoctrine()->getManager();
            $em->persist($voyage);
            $em->flush();
            $flashy->success("Voyage Modified successfully");


            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }
        return $this->render('voyage/index.html.twig');
    }



    /**
     * @Route("/AddVoyage", name="AjouterVoyage")
     */
    public function addVoyage(Request $request,FlashyNotifier $flashy): Response
    {
        $voyage = new Voyage();

        $form = $this->createForm(VoyageType::class,$voyage);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($voyage);
            $em->flush();
            $flashy->success("Voyage added successfully");

            return $this->redirectToRoute('ConsulterVoyage');
        }

        return $this->render('voyage/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/ConsulterVoyage", name="ConsulterVoyage")
     */
    public function displayVoyages(): Response
    {
        $voyages = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();


        return $this->render('voyage/display.html.twig', [
            'voyages'=>$voyages]);

    }

    /**
     * @Route("/ConsulterVoyagesUser", name="ConsulterVoyagesUser")
     */
    public function displayVoyagesUser(): Response
    {
        $voyages = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();


        return $this->render('voyage/displayUser.html.twig', [
            'voyages'=>$voyages]);
    }

    /**
     * @Route("/searchDoctorajax", name="ajaxDoctor")
     */
    public function searchajax(Request $request ,VoyageRepository $PartRepository)
    {
        $requestString=$request->get('searchValue');
        $jeux = $PartRepository->findVoyage($requestString);

        return $this->render('voyage/ajax.html.twig', [
            "voyages"=>$jeux,
        ]);
    }
    /**
     * @Route("/ConsulterVoyageUser/{id}", name="ConsulterVoyageUser")
     */
    public function displayVoyageUser($id): Response
    {
        $voyage = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->find($id);

        return $this->render('voyage/displayVoyageUser.html.twig', [
            'voyage'=>$voyage]);
    }

    /**
     * @Route("DeleteVoyage/{id}",name="DeleteVoyage")
     */
    function DeleteVoyage($id,FlashyNotifier $flashy)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Voyage::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        $flashy->error("Voyage Deleted successfully");

        return $this->redirectToRoute('ConsulterVoyage');

    }

    /**
     * @Route("ModifierVoyage/{id}",name="ModifierVoyage")
     */
    function ModifierVoyage($id,Request $request,FlashyNotifier $flashy)
    {
        $voyage=$this->getDoctrine()->getManager()->getRepository(Voyage::class)->find($id);
        $form=$this->createForm(VoyageType::class,$voyage);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $flashy->warning("Voyage Modified successfully");

            return $this->redirectToRoute("ConsulterVoyage");
        }
        return $this->render("voyage/modifier.html.twig",['f'=>$form->createView()]);
    }
}
