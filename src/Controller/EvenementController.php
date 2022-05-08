<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\EventRate;
use App\Entity\Recherche;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\EvenementType;
use App\Form\EventRateType;
use App\Form\RechercheType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{




    /**
     * @Route("/AjouterEvenement/admin", name="AjouterEvenement")
     */
    public function addEvenement(Request $request): Response
    {
        $evenement = new Evenement();

        $form = $this->createForm(EvenementType::class,$evenement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //5edmat image
            $image = $form->get('Image')->getData();

            if ($image) {
                $newFilename = uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter("images_directory"),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $evenement->setImage($newFilename);
                $em = $this->getDoctrine()->getManager();
                $em->persist($evenement);
                $em->flush();
                return $this->redirectToRoute('ShowEvenement');
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($evenement);
                $em->flush();
                return $this->redirectToRoute('ShowEvenement');
            }
        }


        return $this->render('evenement/ajouter.html.twig', [
            'f'=>$form->createView()]);

    }

    /**
     * @Route("/ShowEvenement/admin", name="ShowEvenement")
     */
    public function showEvenement(Request $request,PaginatorInterface $paginator): Response
    {
        $events = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        //pagination
        $events=$paginator->paginate(
            $events, //on passe les données
            $request->query->getInt('page', 1), //num de la page en cours, 1 par défaut
            3);//nbre d'articles par page

        return $this->render('evenement/show.html.twig', [
            'events'=>$events]);

    }

    /**
     * @Route("/ShowETopvenement/admin", name="ShowETopvenement")
     */
    public function showTopEvenement(ReservationRepository $repository): Response
    {
        $result=$repository->GetTop3Events();
        $events = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

        return $this->render('evenement/showtop.html.twig', [
            'events'=>$events,'result'=>$result]);

    }


    /**
     * @Route("/showevent/{id}/user", name="showevent")
     */
    public function showevent($id,Request $request): Response
    {
        $iduser=1;
        $event = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
        $reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>$iduser,'id_evenement'=>$id]);
        if($reservation != null) {
            $event->setReserved(1);
        }
        $rate = $this->getDoctrine()->getManager()->getRepository(EventRate::class)->findOneBy(['id_Event'=>$id,'id_user'=>$iduser]);
        if($rate==null){
            $rate = new EventRate();
            $rate->setRate(0);
        }
        $form = $this->createForm(EventRateType::class, $rate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
            $rate->setIdUser($user);
            $rate->setIdEvent($event);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rate);
            $em->flush();
            return $this->redirectToRoute('showevent',['id'=>$id]);
        }

        return $this->render('evenement/index.html.twig', [
            'event' => $event,'user'=>$user,'iduser'=>$iduser,'form1' => $form->createView(),'ratings'=>$rate->getRate()
        ]);
    }

    /**
     * @Route("/ShowEvenementUser/user", name="ShowEvenementUser")
     */
    public function showEvenementuser(Request $request,EvenementRepository $evenementRepository): Response
    {
        $iduser=1;
        $filter=new Recherche();
        $form = $this->createForm(RechercheType::class,$filter);
        $form->handleRequest($request);
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
        $reservations=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>$iduser]);
        $events = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        $rating =$this->getDoctrine()->getManager()->getRepository(EventRate::class)->findAll();

        foreach ($reservations as $reservation) {
            foreach ($events as $event) {
                if ($event->getId() == $reservation->getIdEvenement()->getId()) {
                    $event->setReserved(1);
                }
            }
        }
        if ($form->isSubmitted() && $form->isValid()){
            $nom=$filter->getNom();
            if($nom!=""){
                $events=$evenementRepository->FindEventByNamewithBiggestOffre($nom);
                $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
                $reservations=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>$iduser]);
                foreach ($reservations as $reservation) {
                    foreach ($events as $event) {
                        if ($event->getId() == $reservation->getIdEvenement()->getId()) {
                            $event->setReserved(1);
                        }
                    }
                }
            }else{
                $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
                $reservations=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>$iduser]);
                $events = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
                foreach ($reservations as $reservation) {
                    foreach ($events as $event) {
                        if ($event->getId() == $reservation->getIdEvenement()->getId()) {
                            $event->setReserved(1);
                        }
                    }
                }
            }
        }
        return $this->render('evenement/showuser.html.twig', [
            'events'=>$events,'user'=>$user,'reservations'=>$reservations,
            'form'=>$form->createView(),'iduser'=>$iduser,'rates'=>$rating]);

    }



    /**
     * @Route("deleteEvenement/{id}/admin",name="deleteEvenement")
     */
    function DeleteEvenement($id)
    {
        $eq=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($eq);
        $em->flush();
        return $this->redirectToRoute('ShowEvenement');

    }

    /**
     * @Route("modifierEvenement/{id}/admin",name="modifierEvenement")
     */
    function modifier ($id,Request $request)
    {
        $event=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
        $form=$this->createForm(EvenementType::class,$event);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
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

                $event->setImage($newFilename);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("ShowEvenement");
            }
            else{
                $event->setLogo(" ");
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute("ShowEvenement");

            }
        }
        return $this->render("evenement/modifier.html.twig",['f'=>$form->createView()]);
    }

    /**
     * @Route("/imprimer_prod/user", name="imprimer_prod")
     */

    public function imprimeprod(EvenementRepository $EvenementRepository): Response

    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');


        $dompdf = new Dompdf($pdfOptions);

        $events= $EvenementRepository->findAll();

        $html = $this->renderView('Evenement/pdfEvent.html.twig', [
            'events' => $events,
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("Liste evenemenets.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('imprimer_prod');

    }

    /**
     * @Route("/eventcalendar/admin", name="eventcalendar")
     */
    public function calendar(EvenementRepository $repository): Response
    {

        $rep = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

        $rdvs = [];
        foreach ($rep as $event)
        {

            $rdvs[]=[
                'id' => $event->getId(),
                'title'=>$event-> getNom(),
                'start'=>$event->getDate()->format('Y-m-d H:i:s'),
                'end'=>$event->getDate()->add(new \DateInterval('PT10H'))->format('Y-m-d H:i:s'),
                'description' => $event->getDescription(),
                'backgroundColor'=> '#dcdcdc',
                'borderColor'=> 'green',
                'textColor' => 'black'
            ];

        }


        $data = json_encode($rdvs);
        return $this->render('evenement/Calenderie.html.twig',compact('data' ));
    }










}
