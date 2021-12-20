<?php

namespace App\Controller;

use App\Entity\BonEntre;
use App\Entity\BonSortie;
use App\Entity\Demontage;
use App\Entity\FicheIntervention;
use App\Entity\LigneBE;
use App\Entity\LigneBS;
use App\Entity\Machine;
use App\Entity\PieceRechange;
use App\Entity\PiecesMachine;
use App\Form\BonEntreType;
use App\Form\BonSortieType;
use App\Form\PieceRechangeType;
use App\Repository\BonEntreRepository;
use App\Repository\BonSortieRepository;
use App\Repository\DemontageRepository;
use App\Repository\FicheInterventionRepository;
use App\Repository\FournisseurRepository;
use App\Repository\MachineRepository;
use App\Repository\PieceRechangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class MagasinController extends AbstractController
{
    /**
     * @Route("/add_piece", name="ajouter_piece")
     * @Route("/piece/{id}/edit", name="modifier_piece")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG", "ROLE_CHEF"})
     */
    public function ajouterPiece( PieceRechange $pieceRechange=null ,Request $request, EntityManagerInterface $manager)
    {

        $etat_p="Piece rechange Bien Modifié  !";
        $etat_n="Echec de modification de la piece  !";
        if (!$pieceRechange) {
            $pieceRechange = new PieceRechange();
            $pieceRechange->setQuantite(0);
            $etat_p="Piece rechange Bien Ajouté  !";
            $etat_n="Echec d'ajout de la piece  !";
        }

        $form = $this->createForm(PieceRechangeType::class, $pieceRechange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try{




                $manager->persist($pieceRechange);
                $manager->flush();}catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_piece',  $etat_n);
            }
            $this->addFlash('success_piece',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);

                $form1 = json_decode($request->request->get("items"), true);
                #$piece=$request->request->get("piece");
                #$pieceRechange=$this->getDoctrine()->getRepository(PieceRechange::class)->findBy(['designation'=>$piece]);
                foreach ($form1 as $item){
                    $machine=$this->getDoctrine()->getRepository(Machine::class)->find($item);
                    $piece_machine= new PiecesMachine();
                    $piece_machine->setPiece($pieceRechange);
                    $piece_machine->setMachine($machine);
                    $manager->persist($piece_machine);

                }
                $manager->persist($pieceRechange);

            $manager->flush();



            return $this->redirectToRoute('liste_piece',['id'=>$pieceRechange->getId()]);
        }
        return $this->render('magasin/AjPiece.html.twig',['formPiece'=>$form->createView(),
            'editMode'=>$pieceRechange->getId()!==null]);

    }
    /**
     * @Route("/piece_list", name="liste_piece")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG","ROLE_CHEF" })
     */
    public function listeTrans(PieceRechangeRepository $repo)
    {


        $pieceRechange=$repo->findAll();
        return $this->render('Table/PieceListe.html.twig', [
            'controller_name' => 'MagasinController', 'Pieces' => $pieceRechange

        ]);

    }
    /**
     * @Route("/piece_delete/{id}" , name="supp_piece")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG", "ROLE_CHEF"})
     */
    public function deleteTrans(PieceRechange $pieceRechange)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(PieceRechange::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($pieceRechange);
        $repo->flush();
        $this->addFlash('success_piece', 'Piece rechange Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_piece');
    }
    /**
     * @Route("/add_bon_sortie", name="ajouter_bon_sortie")
     * @Route("/bon_sortie/{id}/edit", name="modifier_bon_sortie")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *@IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG","ROLE_CHEF"})
     */
    public function ajouterBonSortie( BonSortie $bonSortie=null ,Request $request, EntityManagerInterface $manager, PieceRechangeRepository $repo, FicheInterventionRepository $repo_inter)
    {
        $etat_p="Bon sortie Bien Modifié  !";
        $etat_n="Echec de modification du bon sortie  !";




        $form = $this->createForm(BonSortieType::class, $bonSortie);
        $form->handleRequest($request);
        if($request->isXmlHttpRequest()) {
            $form1 = $request->request->get("bon_sortie");
#dump($form1);exit;
            if ($form1["id"]!== null) {
                if ($form1["intervention"]== null ){
                    return new JsonResponse([
                        'success' => false
                    ]);
                }

                $bonSortie = new BonSortie();
                $etat_p="Bon sortie Bien Ajouté  !";
                $etat_n="Echec d'ajout du bon sortie  !";
            }
            $inter=$repo_inter->find($form1["intervention"]);
            $bonSortie->setIntervention($inter);
            $piece = json_decode($request->request->get("items"), true);

            foreach ($piece as $item) {
                $p = $repo->find($item["Id"]);

                $ligne = new LigneBS();
                $ligne->setPiece($p);
                $ligne->setQuantite($item["Quantité"]);
                $ligne->setBonSortie($bonSortie);
                $p->setQuantite($p->getQuantite()-$item["Quantité"]);
                $manager->persist($p);
                $manager->persist($ligne);
                $manager->flush();
            }

            $manager->persist($bonSortie);
            $manager->flush();

            return new JsonResponse([
                'success' => true
            ]);
        }
        return $this->render('magasin/AjBonSortie.html.twig',['formBonSor'=>$form->createView(),

            'pieces' => $manager->getRepository(PieceRechange::class)->findAll()]);
    }
    /**
     * @Route("/bon_sortie_list", name="liste_bon_sortie")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG","ROLE_CHEF"})
     */
    public function listeBonSortie(BonSortieRepository $repo)
    {

        $bonsortie=$repo->findAll();
        return $this->render('Table/BonSortieListe.html.twig', [
            'controller_name' => 'MagasinController', 'BonSor' => $bonsortie

        ]);
    }


    /**
     * @Route("/bon_sortie_delete/{id}" , name="supp_bon_sortie")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG","ROLE_CHEF"})
     */
    public function deleteBonSortie(BonSortie $bonSortie)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(BonSortie::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($bonSortie);
        $repo->flush();
        $this->addFlash('success_bonsor', 'Bon sortie Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_bon_sortie');
    }

    /**
     * @Route("/add_bon_entre", name="ajouter_bon_entre")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG","ROLE_CHEF"})
     */
    public function ajouterBonEntre( BonEntre $bonEntre=null ,Request $request, EntityManagerInterface $manager,PieceRechangeRepository $repo, DemontageRepository $repo_demo, FournisseurRepository $repo_four)
    {
        $etat_p="Bon d'entré Bien Modifié  !";
        $etat_n="Echec de modification de bon d'entré  !";


        $form = $this->createForm(BonEntreType::class, $bonEntre);
        $form->handleRequest($request);
        $etat=0;
        if($request->isXmlHttpRequest()) {
            $form1 = $request->request->get("bon_entre");
#dump($form1);exit;
            if ($form1["id"]!== null) {
                if ($form1["demontage"]== null && $form1["fournisseur"]== null ){
                    return new JsonResponse([
                        'success' => false
                    ]);
                }
                $etat=1;
                $bonEntre = new BonEntre();
                $etat_p="Bon d'entré Bien Ajouté  !";
                $etat_n="Echec d'ajout de bon d'entré  !";
                $bonEntre->setDate(new \DateTime());
            }
            $demo=$repo_demo->find((int)$form1["demontage"]);
            $four=$repo_four->find((int)$form1["fournisseur"]);
            $bonEntre->setDemontage($demo);
            $bonEntre->setFournisseur($four);
            $piece = json_decode($request->request->get("items"), true);

            foreach ($piece as $item) {
                $p = $repo->find($item["Id"]);

                $ligne = new LigneBE();
                $ligne->setPiece($p);
                $ligne->setQuantite($item["Quantité"]);
                $ligne->setBonEntre($bonEntre);
                $p->setQuantite($p->getQuantite()+$item["Quantité"]);
                $manager->persist($p);
                $manager->persist($ligne);
                $manager->flush();
            }

            $manager->persist($bonEntre);
            $manager->flush();

            return new JsonResponse([
                'success' => true
            ]);
        }

        return $this->render('magasin/AjBonEntre.html.twig',['formBonEntre'=>$form->createView(),
            'editMode'=>$etat==0,
            'pieces' => $manager->getRepository(PieceRechange::class)->findAll()
           ]
        );

    }
    /**
     * @Route("/bon_entre_list", name="liste_bon_entre")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG", "ROLE_CHEF"})
     */
    public function listeBonEntre(BonEntreRepository $repo)
    {

        $bonEntre=$repo->findAll();
        return $this->render('Table/BonEntreListe.html.twig', [
            'controller_name' => 'MagasinController', 'Entres' => $bonEntre

        ]);
    }
    /**
     * @Route("/bon_entre_delete/{id}" , name="supp_bon_entre")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG","ROLE_CHEF"})
     */
    public function deleteBonEntre(BonEntre $bonEntre)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(BonEntre::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($bonEntre);
        $repo->flush();
        $this->addFlash('success_bonentre', 'Bon entré Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_bon_entre');
    }
    /**
     * @Route("/piece" , name="piece")
     *
     *
     */
    public function piece(Request $request)
    {
        $form=$request->request->get("bon_sortie");
        $data=$form['intervention'];

        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $pieceRepository = $em->getRepository(PieceRechange::class);
        $intervention=$em->getRepository(FicheIntervention::class)->findOneBy(['id'=>$data]);


        #$pieces=$this->getDoctrine()->getRepository(PiecesMachine::class)->findBy(['machine'=>  $intervention->getMachine()]);

       /* $pieces = $pieceRepository->createQueryBuilder("q")
            ->select("q")
            ->where("q.id = :emplacementid")
            ->setParameter("emplacementid", $data)
            ->getQuery()
            ->getResult();*/

       $pieces=$intervention->getMachine()->getPiecesMachines();
       $object=$this->getDoctrine()->getRepository(PiecesMachine::class)->findBy(['machine'=>$intervention->getMachine()]);

$tab=array();
        $responseArray = array();
        foreach ($pieces as $piece){

            $element=$pieceRepository->find($piece->getPiece()) ;
            #$tab[]=$piece->getPiece()->getDesignation();
            #dump($tab);exit;
            $responseArray[]=array(
                "id"=>$piece->getPiece()->getId(),
                "name"=>$piece->getPiece()->getDesignation()
            );
        }

        return new JsonResponse($responseArray);
    }

}
