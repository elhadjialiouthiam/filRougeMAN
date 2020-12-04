<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\GroupeTag;

class GroupeTagDataPersister implements DataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof GroupeTag;
    }

    /**
     * @param GroupeTag $data
     */
    public function persist($data)
    {
        // TODO: Implement persist() method.
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @param GroupeTag $data
     */
    public function remove($data)
    {
        // TODO: Implement remove() method.
        $data->SetStatut(true);
        $this->entityManager->flush();
    }
}