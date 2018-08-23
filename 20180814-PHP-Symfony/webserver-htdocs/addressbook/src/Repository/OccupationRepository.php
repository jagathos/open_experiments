<?php

namespace App\Repository;

use App\Entity\Occupation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Occupation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Occupation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Occupation[]    findAll()
 * @method Occupation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OccupationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Occupation::class);
    }
}
