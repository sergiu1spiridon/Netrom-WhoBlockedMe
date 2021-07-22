<?php


namespace App\Service;


use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;

class RatingService
{
    /**
     * @var RatingRepository
     */
    private $ratingRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * RatingService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->ratingRepository = $em->getRepository(Rating::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByRatedAndRater(string $rated,string $rater):?Rating {
        return $this->ratingRepository->createQueryBuilder('r')
            ->andWhere('r.ratedId = :rated')
            ->setParameter('rated', $rated)
            ->andWhere('r.raterId = :rater')
            ->setParameter('rater', $rater)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getRatingOfUser($userId):int
    {
        $finalRating = 0;
        $ratingsArray = $this->ratingRepository->findByRated($userId);

        if (sizeof($ratingsArray) != 0) {
            foreach ($ratingsArray as $rating) {
                $finalRating += $rating->getRating();
            }

            $finalRating /= sizeof($ratingsArray);
        }

        return $finalRating;
    }


}