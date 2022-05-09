<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\HebergementRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ReservationController
 * @package App\Controller
 * @Route("/res", name="res")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(ReservationRepository $repository): Response
    {
        $reservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAll();
        $list1=$repository->calcul("Acceptée");
        $total1=0;
        foreach ($list1 as $row){
            $total1++;
        }
        $list2=$repository->calcul("Annulée");
        $total2=0;
        foreach ($list2 as $row){
            $total2++;
        }
        $list3=$repository->calcul("En Cours");
        $total3=0;
        foreach ($list3 as $row){
            $total3++;
        }
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
                ['Acceptée',     $total1],
                ['Annulée',    $total2],
                ['En Cours',    $total3]
            ]
        );
        $pieChart->getOptions()->setTitle('Reservation activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('reservation/index.html.twig', [
            'reservation' => $reservations,
            'piechart' => $pieChart,
        ]);
    }

    /**
     * @Route ("/reservation/Add/{idH}", name="AddReservation")
     */
    public function add(Request $request,$idH,UserRepository $repositoryUser,HebergementRepository $hebergementRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $hebergement=$hebergementRepository->find($idH);
        $user=$repositoryUser->find(1);
        $reservation->setUser($user);
        $reservation->setEtat("En Cours");
        $reservation->setHebergement($hebergement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
            return $this->redirectToRoute('dsdhebergementyy');
        }

        return $this->render('reservation/add.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reservation/{id}", name="reservationShow")
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
    /**
     * @Route("/reservations/{id}", name="print")
     */
    public function print(Reservation $reservation)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reservation/show.html.twig', [
            'title' => "Welcome to our PDF Test",
            'reservation' => $reservation
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }
    /**
     * @Route("/{id}/edit", name="reservationEdit")
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('resreservation');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editAcc", name="reservationEditAcc")
     */
    public function editAcc(Request $request, Reservation $reservation): Response
    {
        $reservation->setEtat("Acceptée");

            $this->getDoctrine()->getManager()->flush();


        return $this->redirectToRoute("resreservation");
    }

    /**
     * @Route("/{id}/editDec", name="reservationEditDec")
     */
    public function editDec(Request $request, Reservation $reservation): Response
    {
        $reservation->setEtat("Annulée");

        $this->getDoctrine()->getManager()->flush();


        return $this->redirectToRoute("resreservation");
    }

    /**
     * @Route("/{id}", name="reservationDelete")
     */
    public function delete(Request $request, Reservation $reservation): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reservation);
        $entityManager->flush();


        return $this->redirectToRoute('resreservation');
    }
}
