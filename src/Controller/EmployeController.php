<?php

namespace App\Controller;

use App\Entity\Magasiniers;
use App\Entity\Techniciens;
use App\Form\MagasinierType;
use App\Form\TechniciensType;
use App\Repository\MagasiniersRepository;
use App\Repository\TechniciensRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class EmployeController extends AbstractController
{
    /**
     * @Route("/ajoutetech", name="ajouter_tech")
     * @Route("/tech_list/{id}/edit", name="modifier_tech")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function ajouterTech(Techniciens $techniciens = null, Request $request, EntityManagerInterface $manager)
    {
        $etat_p = "Technicien Bien Modifié  !";
        $etat_n = "Echec de modification du Technicien  !";
        if (!$techniciens) {
            $techniciens = new Techniciens();
            $etat_p = "Technicien Bien Ajouté  !";
            $etat_n = "Echec d'ajout du Technicien  !";
        }

        $form = $this->createForm(TechniciensType::class, $techniciens);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $manager->persist($techniciens);
                $manager->flush();
            } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_tech', $etat_n);
            }
            $this->addFlash('success_tech', $etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);


            return $this->redirectToRoute('liste_tech', ['id' => $techniciens->getId()]);
        }
        return $this->render('employe/AjTech.html.twig', ['formTech' => $form->createView(),
            'editMode' => $techniciens->getId() !== null]);
    }

    /**
     * @Route("/tech_list", name="liste_tech")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeTech(TechniciensRepository $repo)
    {

        $technicien = $repo->findAll();
        return $this->render('Table/techListe.html.twig', [
            'controller_name' => 'EmployeController', 'techs' => $technicien
        ]);
    }

    /**
     * @Route("/ajoutemag", name="ajouter_mag")
     * @Route("/ajoutemag/{id}/edit", name="modifier_mag")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function ajouterMag(Magasiniers $magasinier = null, Request $request, EntityManagerInterface $manager)
    {
        $etat_p = "Magasinier Bien Modifié  !";
        $etat_n = "Echec de modification du Magasinier  !";
        if (!$magasinier) {
            $magasinier = new Magasiniers();
            $etat_p = "Magasinier Bien Ajouté  !";
            $etat_n = "Echec d'ajout du Magasinier  !";
        }

        $form = $this->createForm(MagasinierType::class, $magasinier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $manager->persist($magasinier);
                $manager->flush();
            } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_mag', $etat_n);
            }
            $this->addFlash('success_mag', $etat_p);
            return $this->redirectToRoute('liste_mag', ['id' => $magasinier->getId()]);


        }
        return $this->render('employe/AjMag.html.twig', ['formMag' => $form->createView(),
            'editMode' => $magasinier->getId() !== null]);
    }

    /**
     * @Route("/mag_list", name="liste_mag")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeMag(MagasiniersRepository $repo)
    {

        $magasinier = $repo->findAll();
        return $this->render('Table/magListe.html.twig', [
            'controller_name' => 'EmployeController', 'mags' => $magasinier
        ]);
    }

    /**
     * @Route("/Technicien_delete/{id}" , name="supp_tech")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteTech(Techniciens $techniciens)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(Techniciens::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($techniciens);
        $repo->flush();
        $this->addFlash('success_tech', 'Technicien Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_tech');
    }

    /**
     * @Route("/Magasinier_delete/{id}" , name="supp_mag")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteMag(Magasiniers $magasiniers)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(Magasiniers::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($magasiniers);
        $repo->flush();
        $this->addFlash('success_mag', 'Magasinier Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_mag');
    }
}
