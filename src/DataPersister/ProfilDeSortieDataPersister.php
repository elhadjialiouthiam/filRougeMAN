<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\ProfilDeSortie;
use App\Repository\ApprenantRepository;

class ProfilDeSortieDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $apprenantRepository;

    public function __construct(EntityManagerInterface $entityManager, ApprenantRepository $apprenantRepository)
    {
        $this->entityManager = $entityManager;
        $this->apprenantRepository = $apprenantRepository;
    }
    
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof ProfilDeSortie;
    }

    /**
     * @param ProfilDeSortie $data
     */
    public function persist($data)
    {
        // TODO: Implement persist() method.
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @param ProfilDeSortie $data
     */
    public function remove($data)
    {
        // TODO: Implement remove() method.
        $data->setStatut(true);

        // loading..
        $apprenants = $this->apprenantRepository->findBy(array("ProfilDeSortie"=>$data));
        foreach ($apprenants as $app) {
            $app->setProfilDeSortie(null);
        }
        
        $this->entityManager->flush();
    }
}