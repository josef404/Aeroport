<?php

namespace App\Controller;

use App\Entity\Batiment;
use App\Entity\Emplacement;
use App\Entity\Entretient;
use App\Entity\Famille;
use App\Entity\Machine;
use App\Entity\SousFamille;
use App\Form\EntretientType;
use App\Form\FamilleType;
use App\Form\MachineType;
use App\Form\SousFamilleType;
use App\Repository\BatimentRepository;
use App\Repository\EntretientRepository;
use App\Repository\FamilleRepository;
use App\Repository\FournisseurRepository;
use App\Repository\MachineRepository;
use App\Repository\SousFamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Json;

class MachineController extends AbstractController
{
    /**
     * @Route("/ajoutefam", name="ajouter_fam")
     * @Route("/fam_list/{id}/edit", name="modifier_fam")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function ajouterFam( Famille $famille=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Famille Bien Modifié  !";
        $etat_n="Echec de modification du famille  !";
        if (!$famille) {
            $famille = new Famille();
            $etat_p="Famille Bien Ajouté  !";
            $etat_n="Echec d'ajout du famille  !";
        }

        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{

                $manager->persist($famille);
                $manager->flush();}catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_fam',  $etat_n);
            }
            $this->addFlash('success_fam',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);


            return $this->redirectToRoute('ajouter_fam',['id'=>$famille->getId()]);
        }
        return $this->render('machine/AjFam.html.twig',['formFam'=>$form->createView(),
            'editMode'=>$famille->getId()!==null]);
    }

    /**
     * @Route("/ajoute_sous_fam", name="ajouter_sous_fam")
     * @Route("/sous_fam_list/{id}/edit", name="modifier_sous_fam")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function ajouterSousFam( SousFamille $sousFamille=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Sous famille Bien Modifié  !";
        $etat_n="Echec de modification du sous famille  !";
        if (!$sousFamille) {
            $sousFamille = new SousFamille();
            $etat_p="Sous famille Bien Ajouté  !";
            $etat_n="Echec d'ajout du sous famille  !";
        }

        $form = $this->createForm(SousFamilleType::class, $sousFamille);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{

                $manager->persist($sousFamille);
                $manager->flush();}catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_sousfam',  $etat_n);
            }
            $this->addFlash('success_sousfam',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);


            return $this->redirectToRoute('liste_sou_fam',['id'=>$sousFamille->getId()]);
        }
        return $this->render('machine/AjSousFam.html.twig',['formSousFam'=>$form->createView(),
            'editMode'=>$sousFamille->getId()!==null]);
    }
    /**
     * @Route("/sous_fam_list", name="liste_sou_fam")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeSouFam(SousFamilleRepository $repo)
    {

        $sousFamille=$repo->findAll();
        return $this->render('Table/SousFamListe.html.twig', [
            'controller_name' => 'MachineController', 'sousFam' => $sousFamille

        ]);
    }
    /**
     * @Route("/sous_fam_delete/{id}" , name="supp_sous_fam")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteSouFam(SousFamille $sousFamille)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(SousFamille::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($sousFamille);
        $repo->flush();
        $this->addFlash('success_sousfam', 'Sous famille Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_sou_fam');
    }
    /**
     * @Route("/fam_list", name="liste_fam")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeFam(FamilleRepository $repo)
    {

        $Famille=$repo->findAll();
        return $this->render('Table/FamListe.html.twig', [
            'controller_name' => 'MachineController', 'Fams' => $Famille

        ]);
    }
    /**
     * @Route("/fam_delete/{id}" , name="supp_fam")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteFam(Famille $Famille)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(FamilleType::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($Famille);
        $repo->flush();
        $this->addFlash('success_fam', 'Famille Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_fam');
    }
    /**
     * @Route("/ajoute_machine", name="ajouter_machine")
     * @Route("/machine_list/{id}/edit", name="modifier_machine")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF", "ROLE_TECH"})
     */
    public function ajouterMachine( Machine $machine=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Machine Bien Modifié  !";
        $etat_n="Echec de modification du machine  !";
        if (!$machine && $this->isGranted('ROLE_CHEF')) {
            $machine = new Machine();

            $etat_p="Machine Bien Ajouté  !";
            $etat_n="Echec d'ajout du machine  !";
            #$machine->setDateProchainEntretient(new \DateTime());
        }elseif (!$machine && $this->isGranted('ROLE_TECH')){
            return $this->redirectToRoute('erreur');
        }


        $form = $this->createForm(MachineType::class, $machine);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try{

                $manager->persist($machine);
               }catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_mach',  $etat_n);
            }
            $this->addFlash('success_mach',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);

            $manager->flush();
            return $this->redirectToRoute('liste_machine',['id'=>$machine->getId()]);
        }
        return $this->render('machine/AjMachine.html.twig',[
            'formMachine'=>$form->createView(),
            'bats'=>$this->getDoctrine()->getRepository(Batiment::class)->findAll(),
            'emps'=>$this->getDoctrine()->getRepository(Emplacement::class)->findAll(),
            'editMode'=>$machine->getId()!==null]);
    }
    /**
     * @Route("/emp", name="emplacement")
     */
    public function emplacement(Request $request){
        $form=$request->request->get("machine");
        $data=$form['emplacement'];

        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $batimentsRepository = $em->getRepository(Batiment::class);

        // Search the neighborhoods that belongs to the city with the given id as GET parameter "cityid"
        $batiments = $batimentsRepository->createQueryBuilder("q")
            ->select("q")
            ->where("q.emplacement = :emplacementid")
            ->setParameter("emplacementid", $data)
            ->getQuery()
            ->getResult();


        $responseArray = array();
foreach ($batiments as $batiment){
    $responseArray[]=array(
        "id"=>$batiment->getId(),
        "name"=>$batiment->getLibelle()
    );
}
#dump($tab);exit;
        return new JsonResponse($responseArray);
    }

    /**
     * @Route("/machine_list", name="liste_machine")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF", "ROLE_TECH"})
     */
    public function listeMachine(MachineRepository $repo, EntityManagerInterface $manager)
    {

        $machine=$repo->findAll();


        return $this->render('Table/MachineListe.html.twig', [
            'controller_name' => 'MachineController', 'Machines' => $machine

        ]);
    }
    /**
     * @Route("/machine_delete/{id}" , name="supp_machine")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteMachine(Machine $machine)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(Machine::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($machine);
        $repo->flush();
        $this->addFlash('success_mach', 'Machine Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_machine');
    }
    /**
     * @Route("/add_entretient", name="ajouter_entretient")
     * @Route("/entretient_list/{id}/edit", name="modifier_entretient")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF", "ROLE_TECH"})
     */
    public function ajouterEntretient( Entretient $entretient=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Entretient Bien Modifié  !";
        $etat_n="Echec de modification de l'entretient  !";
        if (!$entretient) {
            $entretient = new Entretient();
            $etat_p="Entretient Bien Ajouté  !";
            $etat_n="Echec d'ajout de l'entretient  !";
        }
$data=$this->getDoctrine()->getRepository(Entretient::class)->findAll();
        $tab=[];
        foreach ($data as $item){
            $tab[]=$item->getMachine();
        }
        $form = $this->createForm(EntretientType::class, $entretient);
        $form->add('machine', EntityType::class, ['placeholder'=>'Select','class'=>Machine::class,
            'query_builder' => function (MachineRepository $er) use($tab)  {
                return $er->createQueryBuilder('u')
                    ->from(Entretient::class, 'm')
                    ->select('u')

                    ->where(" u.id NOT IN (:machine)  ")
                    ->setParameter("machine", $tab);



            },
            'choice_label'=>'libelle',]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{

                $manager->persist($entretient);
                $manager->flush();}catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error_entr',  $etat_n);
            }
            $this->addFlash('success_entr',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);


            return $this->redirectToRoute('liste_entretient',['id'=>$entretient->getId()]);
        }
        return $this->render('machine/AjEntretient.html.twig',['formEnt'=>$form->createView(),
            'editMode'=>$entretient->getId()!==null]);
    }
    /**
     * @Route("/entretient_delete/{id}" , name="supp_entretient")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function deleteEntretient(Entretient $entretient)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(Entretient::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($entretient);
        $repo->flush();
        $this->addFlash('success_ent', 'Entretient Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('liste_entretient');
    }
    /**
     * @Route("/entretient_list", name="liste_entretient")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN",  "ROLE_CHEF"})
     */
    public function listeEntretient(EntretientRepository $repo)
    {
        $entretient=$repo->findAll();

        return $this->render('Table/ListeEnt.html.twig', [
             'Ent' => $entretient,


        ]);
    }
}
