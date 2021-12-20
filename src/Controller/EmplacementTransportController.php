<?php

namespace App\Controller;

use App\Entity\Batiment;
use App\Entity\Emplacement;

use App\Form\EmplacementType;

use App\Repository\BatimentRepository;
use App\Repository\EmplacementRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class EmplacementTransportController extends AbstractController
{
    /**
     * @Route("/ajoute_emp", name="ajouter_emp")
     * @Route("/emp_list/{id}/edit", name="modifier_emp")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function ajouterEmp( Emplacement $emplacement=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Emplacement Bien Modifié  !";
        $etat_n="Echec de modification de l'emplacement  !";
        if (!$emplacement) {
            $emplacement = new Emplacement();
            $etat_p="Emplacement Bien Ajouté  !";
            $etat_n="Echec d'ajout de l'emplacement  !";
        }

        $form = $this->createForm(EmplacementType::class, $emplacement);
        $form->handleRequest($request);
        if($request->isXmlHttpRequest()) {
            $form1 = $request->request->get("emplacement");
#dump($form1);exit;
            if ($form1["id"]!== null) {
                if ($form1["libelle"]== null || $form1["libelle"]== null || $form1["ville"]== null || $form1["description"]== null){
                    return new JsonResponse([
                        'success' => false
                    ]);
                }

                $emplacement = new Emplacement();
                $etat_p="Emplacement Bien Ajouté  !";
                $etat_n="Echec d'ajout de l'emplacement  !";
            }

            $emplacement->setLibelle($form1["libelle"]);
            $emplacement->setVille($form1["ville"]);
            $emplacement->setDescription($form1["description"]);

            $batiment = json_decode($request->request->get("items"), true);

            foreach ($batiment as $item) {
                $ligne = new Batiment();
                $ligne->setLibelle($item["Batiment"]);
                $ligne->setEmplacement($emplacement);
                $manager->persist($ligne);
                $manager->flush();
            }

            $manager->persist($emplacement);
            $manager->flush();

            return new JsonResponse([
                'success' => true
            ]);
        }
        return $this->render('emplacement_transport/AjEmp.html.twig',['formEmp'=>$form->createView(),
           ]);
    }
    /**
     * @Route("/emp_list", name="liste_emp")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeEmp(EmplacementRepository $repo, BatimentRepository $repo_bat)
    {

        $emplacement=$repo->findAll();
        $batiment= $repo_bat->findAll();
        return $this->render('Table/EmpListe.html.twig', [
             'Emps' => $emplacement,
             'Bats' => $batiment

        ]);
    }
    /**
     * @Route("/emp_delete/{id}" , name="supp_emp")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteEmp(Emplacement $emplacement)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(Emplacement::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($emplacement);
        $repo->flush();
        $this->addFlash('success_emp', 'Emplacement Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_emp');
    }


}
