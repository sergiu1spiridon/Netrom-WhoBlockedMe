<?php


namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 * @ORM\Table(name="activity")
 * @ORM\Entity
 */
class Activity
{
    /**
     * @var string
     *
     * @ORM\Column(name="blocker", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $blocker;

    /**
     * @var string
     *
     * @ORM\Column(name="blockee", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $blockee;


    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @return mixed
     */
    public function getBlocker()
    {
        return $this->blocker;
    }

    /**
     * @param mixed $blocked
     * @return Activity
     */
    public function setBlocker($blocked)
    {
        $this->blocker = $blocked;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlockee()
    {
        return $this->blockee;
    }

    /**
     * @param mixed $blockee
     * @return Activity
     */
    public function setBlockee($blockee)
    {
        $this->blockee = $blockee;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Activity
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }





}