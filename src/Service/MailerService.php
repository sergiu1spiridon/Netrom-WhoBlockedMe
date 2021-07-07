<?php


namespace App\Services;


class MailerService
{

    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(EntityManagerInterface $mailer)
    {
        $this->em = $mailer;
    }

    // TO DO

}