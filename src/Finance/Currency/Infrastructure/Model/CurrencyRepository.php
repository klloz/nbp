<?php

namespace App\Finance\Currency\Infrastructure\Model;

use App\Finance\Currency\Domain\Model\Currency;
use App\Finance\Currency\Domain\Repository\CurrencyRepositoryContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

final class CurrencyRepository extends ServiceEntityRepository implements CurrencyRepositoryContract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function findByISO(string $code, \DateTimeInterface $date): ?Currency
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->eq('c.currencyCode', ':code'))
            ->andWhere($qb->expr()->eq('c.date', ':date'))
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('code', trim($code));

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    public function store(Currency $model): self
    {
        $this->getEntityManager()->persist($model);

        return $this;
    }

    public function flush(): self
    {
        $this->getEntityManager()->flush();

        return $this;
    }
}
