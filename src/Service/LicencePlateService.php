<?php


namespace App\Service;


use App\Entity\LicencePlate;
use Doctrine\ORM\EntityManagerInterface;

class LicencePlateService
{
    /**
     * @var $licencePlateRepository
     */
    protected $licencePlateRepository;
    private EntityManagerInterface $em;


    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->licencePlateRepository = $em->getRepository(LicencePlate::class);
    }

    /**
     * @param string $id
     * @return string[]
     */
    public function findLicencePlatesByUserId(string $id): array
    {

        $func = function (LicencePlate $value) {
            return $value->getPlateNumber();
        };

        return array_map($func, $this->licencePlateRepository->findAllByUser($id));
    }



}