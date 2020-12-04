<?php

namespace App\DataPersister;

use App\Entity\Groupe;
use App\Service\UserService;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class GroupeDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $groupeRepo;
    private $apprenantRepo;

    public function __construct(

        EntityManagerInterface $entityManager ,GroupeRepository $groupeRepo, ApprenantRepository $apprenantRepo
    )
    {
        $this->entityManager = $entityManager;
        $this->groupeRepo = $groupeRepo;
        $this->apprenantRepo = $apprenantRepo;
    }

    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Groupe;

    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data,array $context = [])
    {
        // TODO: Implement remove() method.
        // $apprenant=$data->getApprenant();
        
        // if($apprenant==null){
        //     $this->entityManager->persist($data);
        //     $this->entityManager->flush;
        // }else if($apprenant!==null){
        //     foreach($apprenant as $app){
        //         $this->entityManager->persist($data);
        //         $this->entityManager->flush();
        //         return new JsonResponse(" Apprenant supprimé avec sucess ",Response::HTTP_CREATED,[],true);
        //     }
        // }
         $this->entityManager->persist($data);
         $this->manager->flush();
    }
}