<?php

namespace App\Controller;

use App\Entity\DemandeIntervention;
use App\Entity\Demontage;
use App\Entity\Emplacement;
use App\Entity\FicheIntervention;
use App\Entity\HistoriqueDemande;
use App\Entity\LigneBS;
use App\Entity\PieceRechange;
use App\Entity\SousTraitant;
use App\Entity\Techniciens;
use App\Entity\User;
use App\Form\DemandeInterventionType;
use App\Form\DemontageType;
use App\Form\FicheInterventionType;
use App\Form\LigneBSType;
use App\Repository\DemandeInterventionRepository;
use App\Repository\DemontageRepository;
use App\Repository\EmplacementRepository;
use App\Repository\FicheInterventionRepository;
use App\Repository\HistoriqueDemandeRepository;
use App\Repository\LigneBSRepository;
use App\Repository\PieceRechangeRepository;
use App\Repository\TechniciensRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class InterventionController extends AbstractController
{
    /**
     * @Route("/add_fiche_inter", name="ajouter_fiche_inter")
     * @Route("/fiche_inter/{id}/edit", name="modifier_fiche_inter")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_TECH", "ROLE_CHEF"})
     */
    public function ajouterFicheInter(FicheInterventionRepository $repo, FicheIntervention $ficheIntervention=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Fiche d'intervention Bien Modifié  !";
        $etat_n="Echec de modification de la fiche  !";
        if (!$ficheIntervention) {
            $ficheIntervention = new FicheIntervention();
            $ficheIntervention->setDateDeclaration(new \DateTime());
            $val=1;


$ficheIntervention->setTech($this->getUser());
            $ficheIntervention->setNumeroIntervention($val);
            $etat_p="Fiche d'intervention Bien Ajouté  !";
            $etat_n="Echec d'ajout de la fiche  !";
        }$user=$this->getUser();

        $form = $this->createForm(FicheInterventionType::class, $ficheIntervention);
        $form->add('techniciens', EntityType::class, [
        'class' => Techniciens::class,
        'query_builder' => function (TechniciensRepository $er) use($user) {
            return $er->createQueryBuilder('u')
                ->from(User::class, 'm')
                ->select('u')

                ->where("u.user != :userid")
                ->setParameter("userid", $user);

        },
        'choice_label' => 'prenom'.'nom',
        'required'=>false,
        'attr' => [
            'class' => 'select2 form-control select2-multiple',
            'multiple' => true
        ],
        'mapped'=>false

    ]);
        $role=$this->isGranted('ROLE_CHEF');
        if ($role){
            $form->add('activation');
            $form->add('soutraitant', EntityType::class, ['placeholder'=>'Select','class'=>SousTraitant::class, 'choice_label'=>'nom', 'required'=>false]);

        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{



                #->setEmplacement($ficheIntervention->getMachine()->getEmplacement());
                $manager->persist($ficheIntervention);
            }catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_fich',  $etat_n);
            }

            $manager->flush();
            $ficheIntervention->setNumeroIntervention($ficheIntervention->getId());
            $manager->persist($ficheIntervention);
            $manager->flush();
            $this->addFlash('success_fich',$etat_p);
            $form1 = json_decode($request->request->get("items"), true);
            foreach ($form1 as $item){
               $element =$manager->getRepository(Techniciens::class)->findOneBy(['id'=>$item]);
                $ficheIntervention->addTechnicien($element);
                $manager->persist($ficheIntervention);

            }

            $manager->flush();

            return $this->redirectToRoute('liste_fiche_inter',['id'=>$ficheIntervention->getId()]);
        }
        return $this->render('intervention/AjFicheInter.html.twig',['formFicheInter'=>$form->createView(),
            'editMode'=>$ficheIntervention->getId()!==null,

            'pieces' => $manager->getRepository(PieceRechange::class)->findAll(),]);

    }

    /**
     * @Route("/fiche_intervention_list", name="liste_fiche_inter")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG", "ROLE_TECH", "ROLE_CHEF"})
     */
    public function listeInter(FicheInterventionRepository $repo)
    {

        $ficheInter=$repo->findAll();

        return $this->render('Table/FicheInterListe.html.twig', [
            'FicheInters' => $ficheInter

        ]);

    }
    /**
     * @Route("/fiche_inter_delete/{id}" , name="supp_fiche_inter")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_TECH", "ROLE_CHEF"})
     */
    public function deleteTrans(FicheIntervention $ficheIntervention)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(FicheIntervention::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($ficheIntervention);
        $repo->flush();
        $this->addFlash('success_fich', 'Fiche Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_fiche_inter');
    }
    /**
     * @Route("/imprime/{id}", name="impr_liste")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG", "ROLE_CHEF", "ROLE_TECH"})
     */
    public function show($id ,FicheInterventionRepository $repo, LigneBSRepository $repo1, TechniciensRepository $repo2)
    {
$lbs=null;
        $product = $repo
            ->find($id);
        foreach ($product->getBonSorties() as $bs){
            $lbs = $repo1
                ->findBy( ['bon_sortie' => $bs]
                );
        }
$tech=$repo2->findAll();


        return $this->render('imprimeInter.html.twig', [
            'FicheInters' => $product,
            'lbs' => $lbs ,
            'techs'=>$tech,

            'date'=> new \DateTime()

        ]);

    }
    /**
     * @Route("/add_fiche_demo", name="ajouter_fiche_demo")
     * @Route("/fiche_demo/{id}/edit", name="modifier_fiche_demo")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_TECH", "ROLE_CHEF"})
     */
    public function ajouterDemontage( Demontage $demontage=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Fiche de demontage Bien Modifié  !";
        $etat_n="Echec de modification de la fiche  !";
        if (!$demontage) {
            $demontage = new Demontage();


            $etat_p="Fiche de demontage Bien Ajouté  !";
            $etat_n="Echec d'ajout de la fiche  !";
        }

        $form = $this->createForm(DemontageType::class, $demontage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{

                $demontage->setEmplacement($demontage->getMachine()->getEmplacement());

                $manager->persist($demontage);
                $manager->flush();}catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_demo',  $etat_n);
            }
            $this->addFlash('success_demo',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);


            return $this->redirectToRoute('liste_fiche_demo',['id'=>$demontage->getId()]);
        }
        return $this->render('intervention/AjDemo.html.twig',['formDemo'=>$form->createView(),
            'editMode'=>$demontage->getId()!==null]);
    }
    /**
     * @Route("/fiche_demo_list", name="liste_fiche_demo")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_MAG", "ROLE_CHEF","ROLE_TECH"})
     */
    public function listeDemo(DemontageRepository $repo)
    {

        $ficheDemo=$repo->findAll();

        return $this->render('Table/FicheDemoListe.html.twig', [
            'FicheDemos' => $ficheDemo

        ]);

    }
    /**
     * @Route("/fiche_demo_delete/{id}" , name="supp_fiche_demo")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_TECH", "ROLE_CHEF"})
     */
    public function deleteDemo(Demontage $ficheDemo)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(Demontage::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($ficheDemo);
        $repo->flush();
        $this->addFlash('success_demo', 'Fiche Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_fiche_demo');
    }

    /**
     * @Route("/add_demande_inter", name="ajouter_demande_inter")
     * @Route("/demande_inter/{id}/edit", name="modifier_demande_inter")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_CHEF"})
     */
    public function ajouterDemandeInter( DemandeIntervention $demandeIntervention=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Demande d'intervention Bien Modifié  !";
        $etat_n="Echec de modification de la demande  !";
        if (!$demandeIntervention) {
            $demandeIntervention = new DemandeIntervention();
            $demandeIntervention->setDate(new \DateTime());
            $historique=new HistoriqueDemande();
            $etat_p="Demande d'intervention Bien Ajouté  !";
            $etat_n="Echec d'ajout de la demande  !";
        }



        $form = $this->createForm(DemandeInterventionType::class, $demandeIntervention);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{
$historique->setDate($demandeIntervention->getDate());
$historique->setDescription($demandeIntervention->getDescription());
$historique->setMachine($demandeIntervention->getMachine());
$historique->setSousTraitant($demandeIntervention->getSousTraitant());

                $manager->persist($historique);
                $manager->persist($demandeIntervention);
                $manager->flush();}catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_demande',  $etat_n);
            }
            $this->addFlash('success_demande',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);

            $form1 = json_decode($request->request->get("items"), true);
            foreach ($form1 as $item){
                $element =$manager->getRepository(Techniciens::class)->findOneBy(['id'=>$item]);
                $demandeIntervention->addTechnicien($element);
                $historique->addTechnicien($element);
                $manager->persist($demandeIntervention);
                $manager->persist($historique);

            }

            $manager->flush();
            return $this->redirectToRoute('liste_demande_inter',['id'=>$demandeIntervention->getId()]);
        }
        return $this->render('intervention/AjDemandeInter.html.twig',['formDemandeInter'=>$form->createView(),
            'editMode'=>$demandeIntervention->getId()!==null]);
    }

    /**
     * @Route("/demande_intervention_list", name="liste_demande_inter")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_TECH","ROLE_CHEF"})
     */
    public function listeDemande(DemandeInterventionRepository $repo)
    {

        $demandeInter=$repo->findAll();

        return $this->render('Table/DemandeInterListe.html.twig', [
            'DemandeInters' => $demandeInter

        ]);

    } /**
     * @Route("/historique_demande_list", name="historique_liste_demande")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_CHEF"})
     */
    public function historiqueListeDemande(HistoriqueDemandeRepository $repo)
    {

        $historiqueInter=$repo->findAll();

        return $this->render('Table/HistoriqueDemandeListe.html.twig', [
            'historiqueDemande' => $historiqueInter

        ]);

    }
    /**
     * @Route("/demande_inter_delete/{id}" , name="supp_demande_inter")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_CHEF", "ROLE_TECH"})
     */
    public function deleteDemande(DemandeIntervention $demandeIntervention)
    {
if($this->getUser()->getUsername()== $demandeIntervention->getTechnicien()->getUser()->getUsername() or $this->isGranted('ROLE_CHEF')){


        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(DemandeIntervention::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($demandeIntervention);
        $repo->flush();
        $this->addFlash('success_demande', 'Demande Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_demande_inter');
    }
        return $this->redirectToRoute('erreur');
}
}