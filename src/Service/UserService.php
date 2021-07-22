<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    private EntityManagerInterface $em;
    private $user;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->userRepository = $em->getRepository(User::class);
        $this->user = $security->getUser();
    }

    public function getCurrentUserMail():string
    {
        return $this->userRepository->find($this->user->getUserIdentifier())->getEmailAddress();
    }

    public function getMailOfUser($userId):string
    {
        return $this->userRepository->find($userId)->getEmailAddress();
    }

    public function getUserById($userId):?User
    {
        return $this->userRepository->find($userId);
    }


}