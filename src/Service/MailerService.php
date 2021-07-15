<?php


namespace App\Service;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;

class MailerService
{

    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    // TO DO

    public function sendRegistrationEmail($userMail, $password) {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($userMail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Who Blocked Me Registration')
            ->text($password)
            ->html('your registration email is ' . $userMail . " and password " . $password);

        $this->mailer->send($email);
    }

    public function sendGetCarEmail($userMail, $text) {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($userMail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('You have been reported to have blocked someone')
            ->text($text)
            ->html('Come get car ' . $text);

        $this->mailer->send($email);
    }

    public function sendHaveBeenBlockedEmail($userMail, $text) {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($userMail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('You have been blocked by someone')
            ->text($text)
            ->html('You have been blocked by ' . $text);

        $this->mailer->send($email);
    }

    public function sendActivityHasBeenDeletedMail($userMail, $text) {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($userMail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Activity involving car ' . $text . 'has been deleted')
            ->text($text)
            ->html('there\'s no need to come for car ' . $text);

        $this->mailer->send($email);
    }

}