<?php

namespace App\Controller;

use App\Entity\DemandeIntervention;
use App\Entity\Emplacement;
use App\Entity\Entretient;
use App\Entity\Machine;
use App\Entity\Magasiniers;
use App\Entity\Techniciens;

use App\Repository\DemandeInterventionRepository;
use App\Repository\EmplacementRepository;
use App\Repository\FicheInterventionRepository;
use App\Repository\MachineRepository;
use App\Repository\PieceRechangeRepository;
use App\Repository\TechniciensRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class BaseController extends AbstractController
{

    /**
     * @Route("/", name="base")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function accueil(DemandeInterventionRepository $repo_demande, PieceRechangeRepository $repo1 , MachineRepository $repo_mach, FicheInterventionRepository $repo_fich)
    {$pieceRechange=$repo1->findAll();
        $demande=$repo_demande->findAll();
$user=$this->getUser()->getUsername();
        $machines=$this->getDoctrine()->getRepository(Entretient::class)->findAll();

        $fonc=0;
        $manque=0;
        $tab=array();

foreach ($machines as $item){ $tab[]=$item->getDateProchainEntretient()->diff(new  DateTime('now'))->invert;
   if($item->getDateProchainEntretient()->diff(new DateTime('now'))->invert<=0){
        $manque++;
    }
    $allmachines = $repo_mach->createQueryBuilder('a')
        // Filter by some parameter if you want
        // ->where('a.published = 1')
        ->select('count(a.id)')
        ->getQuery()
        ->getSingleScalarResult();
$fonc=$allmachines-$manque;
}#dump($tab);exit;
        $fiches=$repo_fich->findBy(['activation'=> 0]);
        $demandes=$repo_demande->findAll();
        #$nbr_machine_fonct= $repo_mach->count(['etat'=>'Fonctionnelle']);
        #$nbr_machine_manq= $repo_mach->count(['etat'=>'Manque entretient !']);
        $fichInter=$repo_fich->findAll();
        $janvier=0;
        $fevrier=0;
        $mars=0;
        $avril=0;
        $mai=0;
        $juin=0;
        $juillet=0;
        $aout=0;
        $september=0;
        $october=0;
        $november=0;
        $december=0;

        $em=$this->getDoctrine()->getManager();
       $repo_tech=$em->getRepository(Techniciens::class);
        $tech = $repo_tech->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repo_mag=$em->getRepository(Magasiniers::class);
        $mag = $repo_mag->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repo_tot=$em->getRepository(\App\Entity\User::class);
        $tot = $repo_tot->createQueryBuilder('a')
            // Filter by some parameter if you want
             ->where("a.roles LIKE :role")
            ->setParameter('role', '%"'.'ROLE_CHEF'.'"%')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $repo_emp=$em->getRepository(Emplacement::class);
        $emp = $repo_emp->createQueryBuilder('a')
            // Filter by some parameter if you want
            // ->where('a.published = 1')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
$diff=0;  $i=0; $tab=[]; $tabm=[];
foreach ($fichInter as $inter){
    if($inter->getDateFin()){
        $date_deb=$inter->getDateDebut();
        $date_fin=$inter->getDateFin();
        $diff=$date_deb->diff($date_fin);
        $minute=$diff->i;
        $heure=$diff->h;
        $jour=($diff->d)*24;
        $m=($diff->m)*30*24;
        $annee=($diff->y)*12*30*24;
      $tab[$i]=$heure+$jour+$m+$annee;
      $tabm[$i]=$minute;
        $i++;
    }


}
$somme=array_sum($tab);
$sommem=array_sum($tabm);


if($sommem>60){
    $somme=$somme+(floor($sommem/60));
    $sommem=$sommem%60;
}
if($i==0){
    $moyenne=0;
    $moyennem=0;
}else{
    $moyenne=number_format($somme/$i,2,',','');
    $moyennem=$sommem/$i;
}





        foreach ($fichInter as $fi){
            $date_decl=$fi->getDateDeclaration();
            $mois=$date_decl->format('m');

           if($mois=="01"){
               $janvier=$janvier+1;
           }
            if($mois=="02"){
                $fevrier=$fevrier+1;
            }
            if($mois=="03"){
                $mars=$mars+1;
            }
            if($mois=="04"){
                $avril=$avril+1;
            }
            if($mois=="05"){
                $mai=$mai+1;
            }
            if($mois=="06"){
                $juin=$juin+1;
            }
            if($mois=="07"){
                $juillet=$juillet+1;
            }
            if($mois=="08"){
                $aout=$aout+1;
            }
            if($mois=="09"){
                $september=$september+1;
            }
            if($mois=="10"){
                $october=$october+1;
            }
            if($mois=="11"){
                $november=$november+1;
            }
            if($mois=="12"){
                $december=$december+1;
            }

        }





if($this->isGranted('ROLE_CHEF')){

    return $this->render('dashboard.html.twig',[

        'fiches'=>$fiches,
        'demandes'=>$demandes,
        'manque'=>$manque,
        'fonc'=>$fonc,
        'janvier'=>$janvier,
        'fevrier'=>$fevrier,
        'mars'=>$mars,
        'avril'=>$avril,
        'mai'=> $mai,
        'juin'=>$juin,
        'juillet'=>$juillet,
        'aout'=>$aout,
        'september'=>$september,
        'october'=> $october,
        'november'=>$november,
        'decemeber'=>$december,
        'tech'=>$tech,
        'mag'=>$mag,
        'tot'=>$tot,
        'emp'=>$emp,
        'moyH'=>$moyenne,
        'moyM'=>$moyennem,



    ]);
}
if($this->isGranted('ROLE_TECH')){
    return $this->render('accueilTech.html.twig', [
        'DemandeInters' => $demande,
        'val' => 5,


    ]);
}if($this->isGranted('ROLE_MAG')){
        return $this->render('accueil.html.twig', [
            'piece' => $pieceRechange,


        ]);
    }

    }

    /**
     * @Route("/erreur", name="erreur")
     */
    public function ind(PieceRechangeRepository $repo)
    {

        return $this->render('erreur404.html.twig');


    }
    /**
     * @Route("/notif", name="notif")
     */
    public function notif()
    {
        $repo=$this->getDoctrine()->getRepository(Techniciens::class);
        $tech=$repo->findAll();
        $nombre=0;
foreach ($tech as $item){
   if ($item->getUser()==$this->getUser()){
      $nombre= $item->getDemandeInterventions()->count();
   }else{
       $nombre=0;
   }
}
        return $this->render('menu.html.twig', [
            // this array defines the variables passed to the template,
            // where the key is the variable name and the value is the variable value
            // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')
            'data' => $nombre,

        ]);


    }

}
