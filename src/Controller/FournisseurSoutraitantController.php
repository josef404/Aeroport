<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\SousTraitant;
use App\Form\FournisseurType;
use App\Form\SousTraitantType;
use App\Repository\FournisseurRepository;
use App\Repository\SousTraitantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class FournisseurSoutraitantController extends AbstractController
{

    /**
     * @Route("/ajoute_fournisseur", name="ajouter_four")
     * @Route("/modifier_four/{id}", name="modifier_four")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function ajouterMag(Fournisseur $fournisseur=null, Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Fournisseur Bien Modifié  !";
        $etat_n="Echec de modification du Fournisseur  !";
        if(!$fournisseur) {
            $fournisseur = new Fournisseur();
            $etat_p="Fournisseur Bien Ajouté  !";
            $etat_n="Echec d'ajout du Fournisseur  !";
        }

        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{
                $manager->persist($fournisseur);
                $manager->flush();
            }catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_four', $etat_n);
            }
            $this->addFlash('success_four',$etat_p);
            return $this->redirectToRoute('liste_four',['id'=>$fournisseur->getId()]);



        }
        return $this->render('fournisseur_soutraitant/AjFour.html.twig',['formFour'=>$form->createView(),
            'editMode'=>$fournisseur->getId()!==null]);
    }

    /**
     * @Route("/four_list", name="liste_four")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeFour(FournisseurRepository $repo)
    {

        $fournisseur=$repo->findAll();
        return $this->render('Table/FourListe.html.twig', [
            'controller_name' => 'FournisseurSoutraitantController', 'fours' => $fournisseur

        ]);
    }


    /**
     * @Route("/Fournisseur_delete/{id}" , name="supp_four")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteMag(Fournisseur $fournisseur)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(Fournisseur::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($fournisseur);
        $repo->flush();
        $this->addFlash('success_four', 'Fournisseur Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_four');
    }

    /**
     * @Route("/ajoute_sout", name="ajouter_sout")
     * @Route("/modifier_sout/{id}", name="modifier_sout")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function ajouterSout(SousTraitant $sousTraitant=null, Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Sous traitant Bien Modifié  !";
        $etat_n="Echec de modification du sous traitant  !";
        if(!$sousTraitant) {
            $sousTraitant = new SousTraitant();
            $etat_p="Sous traitant Bien Ajouté  !";
            $etat_n="Echec d'ajout du sous traitant  !";
        }

        $form = $this->createForm(SousTraitantType::class, $sousTraitant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{
                $manager->persist($sousTraitant);
                $manager->flush();
            }catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_sout', $etat_n);
            }
            $this->addFlash('success_sout',$etat_p);
            return $this->redirectToRoute('liste_sout',['id'=>$sousTraitant->getId()]);



        }
        return $this->render('fournisseur_soutraitant/AjSout.html.twig',['formSout'=>$form->createView(),
            'editMode'=>$sousTraitant->getId()!==null]);
    }

    /**
     * @Route("/sout_list", name="liste_sout")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeSout(SousTraitantRepository $repo)
    {

        $soutraitant=$repo->findAll();
        return $this->render('Table/SoutListe.html.twig', [
            'controller_name' => 'FournisseurSoutraitantController', 'souts' => $soutraitant

        ]);
    }
    /**
     * @Route("/sout_delete/{id}" , name="supp_sout")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteSout(SousTraitant $sousTraitant)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(SousTraitant::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($sousTraitant);
        $repo->flush();
        $this->addFlash('success_sout', 'Sous traitant Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_sout');
    }
}
