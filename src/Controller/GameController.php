<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Joueur;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {     $entityManager = $this->getDoctrine()->getManager();
        $game = new Game();
        $game->setType('Jeu de course de voitures arcade');
        $game->setTitre('Nitro Rush: Velocity Mayhem');
        $game->setNbrJoueur('8');
        $game->setEditeur(' Turbo Dynamics Gaming');
        

        $image = new Image();
        $image->setUrl('Images/jouer.jpg');
        $image->setAlt('job de reves');
        $game->setImage($image);
        //Ajout de joueurs

        $joueur1 = new Joueur();
        $joueur1->setNom("Eya");
        $joueur1->setEmail("eya@gmail.com");
        $joueur1->setBornAt(new \DateTime());
        $joueur1->setScore(150);
        $joueur2 = new Joueur();
        $joueur2->setNom("Hanin");
        $joueur2->setEmail("hanin@gmail.com");
        $joueur2->setBornAt(new \DateTime());
        $joueur2->setScore(150);
        $joueur1->setGame($game);
        $joueur2->setGame($game);
        $entityManager->persist($joueur1);
        $entityManager->persist($joueur2);

        $entityManager->persist($game->getImage());

        $entityManager->persist($game);
        $entityManager->flush();
        return $this->render('game/index.html.twig', [
            'id' => $game->getId(),
        ]);

 }
     /**
* @Route("/game/{id}", name="game_show")
*/
public function show($id)
{
 $game = $this->getDoctrine()->getRepository(Game::class)->find($id);
 $em=$this->getDoctrine()->getManager();
 $listJoueurs=$em->getRepository(joueur::class)->findBy(['Game'=>$game]);
 if (!$game) {
 throw $this->createNotFoundException(
 'No game found for id '.$id
 );
 }
 return $this->render('game/show.html.twig', [
 'Game' =>$game,
 'listJoueurs'=>$listJoueurs,
 ]);

    }
   /**
* @Route("/Ajouter" , name="Ajouter")
*/
public function ajouter(Request $request)
{
    $joueur = new Joueur();
    $fb = $this->createFormBuilder($joueur)
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'form-control  '], // Add CSS class for styling
        ])
        ->add('email', TextType::class, [
            'attr' => ['class' => 'form-control  '], // Add CSS class for styling
        ])
        ->add('born_At', DateType::class, [
            'attr' => ['class' => 'form-control '], // Add CSS class for styling
        ])
        ->add('score', IntegerType::class, [
            'attr' => ['class' => 'form-control'],
        ])
        
        ->add('Game', EntityType::class, [
            'class' => Game::class,
            'choice_label' => 'type',
            'attr' => ['class' => 'form-control '], // Add CSS class for styling
        ])
        ->add('Valider', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary'], // Add CSS class for styling
        ]);

// générer le formulaire à partir du FormBuilder
$form = $fb->getForm();
$form -> handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
   $em = $this->getDoctrine()->getManager();
   $em->persist($joueur);
   $em->flush();
   return $this->redirectToRoute('home');

}

// Utiliser la methode createView() pour que l'objet soit exploitable par la vue
return $this->render('Game/ajouter.html.twig',
['f' => $form->createView()] );
}


/** 
*  @Route("/add",name="ajout_game")
*/ 
public function ajouter2(Request $request)
{ $game=new Game();
    $form = $this ->createForm("App\Form\GameType",$game);
    $form -> handleRequest($request);
    if ($form->isSubmitted()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($game);
      $em->flush();
      return $this-> redirectToRoute('home');
    }
    return $this->render('game/ajouter.html.twig', ['f'=>$form->createView()]);

    }
    /** 
*  @Route("/",name="home") 
*/ 

public function home(Request $request)
{      //creation du champ critere
    $form= $this->createFormBuilder()
    //->add("critere",TextType::class)
   // ->add("Valider",SubmitType::class)
    ->getForm();
    $form->handleRequest($request);
    $em = $this->getDoctrine()->getManager();
    $repo =  $em ->getRepository(Joueur::class);
    $lesJoueurs = $repo->findAll();
    //lancer la recherche quand on clique sur le bouton
    if ($form->isSubmitted())
    {
        $data = $form->getData();//valeur de critere
        $lesJoueurs = $repo->recherche($data['critere']);
    }
    return $this->render('game/home.html.twig', ['lesJoueurs'=>$lesJoueurs,'form'=>$form->createView()]);

}

/** 
*  @Route("/supp/{id}",name="jou_delete")
*/ 

public function delete (Request $request,$id): Response{
    $c = $this->getDoctrine()
    ->getRepository(Joueur::class)
    ->find($id);
     if (!$c){
        throw $this->createNotFoundException(
            'No job found for id '.$id);
    
     }
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager -> remove($c);
     $entityManager ->flush();
     return $this->redirectToRoute('home');

}


/**
* @Route("/editU/{id}", name="edit_gamer")
* Method({"GET","POST"})
*/
public function edit(Request $request, $id)
{ $joueur = new Joueur();
$joueur = $this->getDoctrine()
->getRepository(Joueur::class)
->find($id);
if (!$joueur) {
throw $this->createNotFoundException(
'No joueur found for id '.$id
);
}
$fb = $this->createFormBuilder($joueur)//bch creer formulaire,formulaire mch feregh # mch kima ajout candidat justeavoir feregh(ajout candidat vide)
->add('nom', TextType:: class)
->add('email', TextType:: class)
->add('born_At', DateType:: class)
->add('Game', EntityType:: class, [
'class' => Game:: class,
'choice_label' => 'type', ])
->add('Valider', SubmitType::class, [
    'attr' => ['class' => 'btn btn-primary'], // Add CSS class for styling
]);
// générer le formulaire à partir du FormBuilder
$form = $fb->getForm();
$form->handleRequest($request);
if ($form->isSubmitted()) {
$entityManager = $this->getDoctrine()->getManager();//pou la modification il suffit un simple flush 
$entityManager->flush();//2 modes de travail un entitymanager fiha les fcts percist , remove , flush ;soit repository(find,findby,findall)
return $this->redirectToRoute('home');
}
return $this->render('game/ajouter.html.twig',
['f' => $form->createView()] );
}




}