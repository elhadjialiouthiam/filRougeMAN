<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    private $grpeRepo;
    private $apprenantRepo;
    private $manager;
    public function __construct( GroupeRepository $grpeRepo, ApprenantRepository $apprenantRepo, EntityManagerInterface $manager){
        $this->grpeRepo = $grpeRepo;
        $this->apprenantRepo = $apprenantRepo;
        $this->manager = $manager;
    }
    /**  
     * @Route(
     * name="removeApprenantFromGroup",
     * methods={"DELETE"},
     * path="api/admin/groupes/{idgroupe}/apprenants/{idapp}",

     * )
     */
        public function removeApprenantFromGroup($idgroupe,$idapp){
        $groupe = $this->grpeRepo->findOneBy(["id"=>$idgroupe]);
        $etudiantWithId = $this->apprenantRepo->findOneBy(["id"=>$idapp]);
        $apprenants = $groupe->getApprenants();
        if($apprenants->contains($etudiantWithId)){
            $groupe->removeApprenant($etudiantWithId);
            $this->manager->flush();
            return new Response("L'apprenant a été supprimé du groupe");
        }
        return new Response("L'apprenant que vous tentez du supprimé est introuvable dans ce groupe");
    }
    
}
