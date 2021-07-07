<?php


namespace App\Services;

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
        $blocker = $this->activityRepo->findByBlocker($licensePlate);

        if ($blocker instanceof Activity){
            return $blocker->getBlockee();
        }
        return '';
    }

    /**
     * @param string $licensePlate
     * @return string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function whoBlockedMe(string $licensePlate): ?string
    {
        $blocker = $this->activityRepo->findByBlockee($licensePlate);

        if ($blocker instanceof Activity){
            return $blocker->getBlocker();
        }
        return '';
    }


}