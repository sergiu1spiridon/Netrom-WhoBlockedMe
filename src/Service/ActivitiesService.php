<?php


namespace App\Service;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;

class ActivitiesService
{
    /**
     * @var ActivityRepository
     */
    protected $activityRepo;
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->activityRepo = $em->getRepository(Activity::class);
    }

    public function iveBlockedSomebody(string $licensePlate)
    {
        $blocker = $this->activityRepo->findOneByBlocker($licensePlate);

        if ($blocker != null){
            return $blocker->getBlockee();
        }
        return '';
    }

    /**
     * @param string $licensePlate
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function whoBlockedMe(string $licensePlate): ?string
    {
        $blocker = $this->activityRepo->findOneByBlockee($licensePlate);

        if ($blocker != null){
            return $blocker->getBlocker();
        }
        return '';
//        return $this->activityRepo->findByBlockee($licensePlate);
    }


}