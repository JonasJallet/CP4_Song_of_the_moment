<?php
 namespace App\Infrastructure\Persistence\Repository;



 use App\Domain\Repository\DomainObjectRepositoryInterface;
 use App\Infrastructure\Persistence\Entity\DoctrineObject;
 use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
 use Doctrine\Persistence\ManagerRegistry;

 /**
  * @method DoctrineObject|null find($id, $lockMode = null, $lockVersion = null)
  * @method DoctrineObject|null findOneBy(array $criteria, array $orderBy = null)
  */
class DoctrineObjectRepository extends ServiceEntityRepository implements DomainObjectRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctrineObject::class);
    }

    public function save($order)
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }

    public function existById(int $id): bool
    {
        $queryBuilder = $this->createQueryBuilder('object')
            ->select('COUNT(object.id)')
            ->Where("object.id LIKE :id")
            ->setParameter('id', $id);
        return 0 !== $queryBuilder->getQuery()->getSingleScalarResult();
    }



}