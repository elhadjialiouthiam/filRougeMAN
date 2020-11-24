<?php

namespace App\DataPersister;

use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class ProfilDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;

    public function __construct(

        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Profil;

    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
        $users=$data->getUsers();
        if($data->getStatut()!==true){
            $data->setStatut(true);
        }
        
        if($users==null){
            $this->entityManager->persist($data);
            $this->entityManager->flush;
        }else if($users!==null){
            foreach($users as $user){
                $user->setStatut(true);
                $this->entityManager->persist($data);
                $this->entityManager->flush();
                return new JsonResponse("Profil et utilisateur relier a cette profil archivee avec sucess ",Response::HTTP_CREATED,[],true);
            }
        }
    }
}