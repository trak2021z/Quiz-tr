<?php

namespace AppBundle\Entity\Repository;

use Doctrine\Common\Cache\RedisCache;
use Doctrine\ORM\Cache;

/**
 * UserAnswerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserAnswerRepository extends \Doctrine\ORM\EntityRepository
{
    public function getQuestionTryNumber($user,$quiz,$question)
    {
        $qb = $this->createQueryBuilder('ua');
        $query = $qb->select('ua.tryNumber')
            ->where('ua.user = :user')
            ->andWhere('ua.quiz = :quiz')
            ->andWhere('ua.question = :question')
            ->setParameter('user', $user)
            ->setParameter('quiz', $quiz)
            ->setParameter('question', $question)
            ->orderBy('ua.id', 'DESC')
            ->setMaxResults(1);
        return $query->getQuery()->getOneOrNullResult();
    }

    public function getPointsForTry()
    {
        $qb = $this->createQueryBuilder('ua');
        $sub = $qb->select('(ua.user) as user, max(ua.tryNumber) as maxNumber')
            ->groupBy('ua.user');
        $query = $qb->select('ua')
            ->addSelect('SUM(ua.points) as sumPoints')
            ->groupBy('ua.tryNumber')
            ->addGroupBy('ua.user')
            ->addGroupBy('ua.quiz')
            ->orderBy('sumPoints', 'DESC')
            ->addOrderBy('ua.tryNumber', 'DESC');
        return $query->getQuery()->getResult();
    }
}
