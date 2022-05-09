<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Commentaire;
use App\Entity\Pictures;
use App\Entity\Place;
use App\Entity\User;
use App\Form\AvisType;
use App\Form\CommentaireType;
use App\Form\PlaceType;
use App\Form\UserType;
use App\Repository\AvisRepository;
use App\Repository\PicturesRepository;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/plc", name="plc")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/place", name="place")
     */
    public function index(PlaceRepository $repository): Response
    {


        return $this->render('place/index.html.twig', [
            'place' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/Userplace", name="Userplace", methods={"GET","POST"} )
     */
    public function indexUser(UserRepository $userRepository,Request $request,PlaceRepository $repository,Request $request1,AvisRepository $avisrepository): Response
    {

        $avis = new Avis();

        $commentaire=new Commentaire();
        $user=$userRepository->find($this->getUser()->getUsername());

        $places=$repository->findAll();
        $avis=$avisrepository->findAll();
        foreach ($avis as $avi) {
            foreach ($places as $place) {
                if ($place->getId() == $avi->getPlace()->getId()) {
                    $place->setIsRated(1);
                }
            }
        }
        return $this->render('place/indexUser.html.twig', [
            'place' => $places,
            'ratings' =>$avis,
        ]);
    }

    /**
     * @Route ("/place/Add", name="AddPlace")
     */
    public function add(Request $request): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($form->get('pictures')->getData() as $image){
                $fichier=md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $picture=new Pictures();
                $picture->setName($fichier);
                $picture->setPlace($place);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($picture);
                $entityManager->flush();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();

            return $this->redirectToRoute('plcplace');
        }

        return $this->render('place/add.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/place/{id}", name="placeShow")
     */
    public function show(Place $place,PicturesRepository $picturesRepository): Response
    {
        $pics=$picturesRepository->findBy(["place"=>$place->getId()]);
        return $this->render('place/show.html.twig', [
            'place' => $place,
            'pic1'=>$pics[0],
            'pictures'=>$pics
        ]);
    }

    /**
     * @Route("/place/user/{id}", name="placeShowUser")
     */
    public function showUser(Request $request,UserRepository $userRepository,Place $place,PicturesRepository $picturesRepository): Response
    {
        $commentaire=new Commentaire();
        $avis=new Avis();

        $user=$userRepository->find($this->getUser()->getUsername());
        $rate = $this->getDoctrine()->getManager()->getRepository(Avis::class)->findOneBy(['Place'=>$place,'user'=>$user]);
        if($rate==null){
            $rate = new Avis();
            $rate->setRate(0);
        }
        $form1 = $this->createForm(CommentaireType::class, $commentaire);
        $form1->handleRequest($request);
        $form = $this->createForm(AvisType::class, $rate);
        $form->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid() ) {
            $commentaire->setUser($user);
            $commentaire->setPlace($place);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('plcplaceShowUser',['id'=>$place->getId()]);
        }
        if ( $form->isSubmitted() && $form->isValid()){
            $rate->setUser($user);
            $rate->setPlace($place);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rate);
            $entityManager->flush();
        }
        $pos=strpos($place->getAdresse()," ");
        $lat=substr($place->getAdresse(),0,$pos+1);
        $lng=substr($place->getAdresse(),$pos,strlen($place->getAdresse()));
        $pics=$picturesRepository->findBy(["place"=>$place->getId()]);
        return $this->render('place/showUser.html.twig', [
            'place' => $place,
            'pic1'=>$pics[0],
            'pictures'=>$pics,
            'form1'=>$form1->createView(),
            'form'=>$form->createView(),
            'comnts'=>$place->getCommentaires(),
            'lat'=>$lat,
            'lng'=>$lng,
            'ratings'=>$rate->getRate()
        ]);
    }

    /**
     * @Route("/Map/user/{id}", name="mapPlace")
     */
    public function showMap(Request $request,UserRepository $userRepository,Place $place,PicturesRepository $picturesRepository): Response
    {

        $pos=strpos($place->getAdresse()," ");
        $lat=substr($place->getAdresse(),0,$pos+1);
        $lng=substr($place->getAdresse(),$pos,strlen($place->getAdresse()));
        $pics=$picturesRepository->findBy(["place"=>$place->getId()]);
        return $this->render('place/map.html.twig', [
            'place' => $place,
            'lat'=>$lat,
            'lng'=>$lng
        ]);
    }

    /**
     * @Route("/{id}/edit", name="placeEdit")
     */
    public function edit(Request $request, Place $place): Response
    {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($form->get('pictures')->getData() as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $picture = new Pictures();
                $picture->setName($fichier);
                $picture->setPlace($place);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($picture);
                $entityManager->flush();
            }
            return $this->redirectToRoute('plcplace');
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/del/{id}/sdf", name="placeDelete")
     */
    public function delete(Request $request, Place $place): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($place);
        $entityManager->flush();


        return $this->redirectToRoute('plcplace');
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(AvisRepository $AvisRepo){
        // On va chercher toutes les catégories
        $places_id = $AvisRepo->CountPlaceId();
        $places = $this->getDoctrine()->getManager()->getRepository(Place::class)->findAll();

        $MuseeName = [];
        $BNName = [];
        $MuseeRate = [];
        $BNRate = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($places_id as $place_id){
            foreach($places as $place){
                if($place_id[2]==$place->getId()){
                    if($place->getType()=="Musee") {
                        $MuseeName[] = $place->getNom();
                        $MuseeRate[] = $place_id[1];

                    }else{
                        $BNName[] = $place->getNom();
                        $BNRate[] = $place_id[1];
                    }
                }
            }
        }

        return $this->render('place/stats.html.twig', [
            'MuseeName' => json_encode($MuseeName),
            'BNName' => json_encode($BNName),
            'MuseeRate' => json_encode($MuseeRate),
            'BNRate' => json_encode($BNRate),

        ]);
    }



}
