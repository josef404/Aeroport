<?php

namespace App\Controller;





use App\Entity\Machine;
use App\Entity\PieceRechange;
use App\Entity\User;
use App\Form\PieceRechangeType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class TableController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="user_table")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN"})
     */
    public function UserTable()
    {

        $repo = $this->getDoctrine()->getManager();


        $userTable = $repo->getRepository(User::class)->findAll();
        return $this->render('Table/utilisateur.html.twig', ['controller_name' => 'TableController', 'userTable' => $userTable]);


    }
    /**
     * @Route("/utilisateur/{id}/edit", name="modifier_utilisateur")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_CHEF"})
     */
    public function modifierUtil( User $user=null ,Request $request, EntityManagerInterface $manager)
    {
        $etat_p="Utilisateur Bien Modifié  !";
        $etat_n="Echec de modification de l'utilisateur  !";


        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try{

                $manager->persist($user);
                $manager->flush();}catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                $this->addFlash('error',  $etat_n);
            }
            $this->addFlash('success',$etat_p);
            # return $this->redirectToRoute('show',['id'=>$techniciens->getId()]);


            return $this->redirectToRoute('user_table',['id'=>$user->getId()]);
        }
        return $this->render('@FOSUser/Registration/register_content.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("/utilisateur_delete/{id}" , name="supp_utilisateur")
     *  @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @IsGranted({"ROLE_SUPER_ADMIN", "ROLE_CHEF"})
     */
    public function deleteUser(User $user)
    {

        $repo = $this->getDoctrine()->getManager();
        $tous_employe = $repo->getRepository(User::class)->findAll();
        #if($techniciens->getDemandeInterventions()==null||$employe->getPreventives()==null){

        $repo->remove($user);
        $repo->flush();
        $this->addFlash('success', 'Utilisateur Bien supprimé !');
        #}
        # else{
        #    $this->addFlash('error', ' Cette Opération est bloquée a cause des contraintes d"integrité  !');
        # }
        return $this->redirectToRoute('user_table');
    }


}
