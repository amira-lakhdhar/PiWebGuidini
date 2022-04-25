<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


class EvenementController extends AbstractController
{


    /**
     * @Route("/showevent/{id}", name="showevent")
     */
    public function showevent($id ): Response
    {
        $event = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>1,'id_evenement'=>$id]);

        return $this->render('evenement/index.html.twig', [
            'event' => $event,'user'=>$user,'reservation'=>$reservation
        ]);
    }


    /**
     * @Route("/AjouterEvenement", name="AjouterEvenement")
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
     * @Route("/ShowEvenement", name="ShowEvenement")
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
         * @Route("/ShowEvenementUser", name="ShowEvenementUser")
     */
    public function showEvenementuser(): Response
    {
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $reservations=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy(['id_user'=>1]);
        $events = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        foreach ($reservations as $reservation) {
            foreach ($events as $event) {
                if ($event->getId() == $reservation->getIdEvenement()->getId()) {
                    $event->setReserved(1);
                }
            }
        }
        return $this->render('evenement/showuser.html.twig', [
            'events'=>$events,'user'=>$user,'reservations'=>$reservations]);

    }

    /**
     * @Route("deleteEvenement/{id}",name="deleteEvenement")
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
     * @Route("modifierEvenement/{id}",name="modifierEvenement")
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
     * @Route("/imprimer_prod", name="imprimer_prod")
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
}

