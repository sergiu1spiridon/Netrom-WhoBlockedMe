<?php

namespace App\Entity;

use App\Repository\LicencePlateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LicencePlateRepository::class)
 */
class LicencePlate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plateNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\OneToMany(targetEntity="User", mappedBy="id", orphanRemoval=false)
     */
    private $userIds = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPlateNumber()
    {
        return $this->plateNumber;
    }

    /**
     * @param mixed $plateNumber
     */
    public function setPlateNumber($plateNumber): void
    {
        $this->plateNumber = $plateNumber;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return null
     */
    public function getUserIds()
    {
        return $this->userIds;
    }

    /**
     * @param null $userIds
     */
    public function setUserIds($userIds): void
    {
        $this->userIds = $userIds;
    }




}
