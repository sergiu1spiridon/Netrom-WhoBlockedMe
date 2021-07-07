<?php


namespace App\Service;


use App\Entity\LicencePlate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class LicencePlateService
{
    /**
     * @var $licencePlateRepository
     */
    protected $licencePlateRepository;
    private EntityManagerInterface $em;
    private $user;


    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->licencePlateRepository = $em->getRepository(LicencePlate::class);
        $this->user = $security->getUser();
    }

    /**
     * @param string $id
     * @return string[]
     */
    public function findLicencePlatesByUserId(): array
    {

        $func = function (LicencePlate $value) {
            return $value->getPlateNumber();
        };


        return array_map($func, $this->licencePlateRepository->findAllByUser($this->user->getUserIdentifier()));
    }

    /**
     * @param string $id
     * @return string[]
     */
    public function findAllUsersByLicencePlate($value): array
    {
        $func = function (LicencePlate $value) {
            return $value->getUserIds();
        };


        return array_map($func, $this->licencePlateRepository->findAllByLicencePlate($value));
    }

}