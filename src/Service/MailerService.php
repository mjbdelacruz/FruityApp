<?php

namespace App\Service;

use App\Exception\MailerException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var string
     */
    private $mailerTo;

    /**
     * @var string
     */
    private $mailerFrom;

    /**
     * @param MailerInterface $mailer
     * @param string $mailerTo
     * @param string $mailerFrom
     */
    public function __construct(MailerInterface $mailer, string $mailerTo, string $mailerFrom)
    {
        $this->mailer = $mailer;
        $this->mailerTo = $mailerTo;
        $this->mailerFrom = $mailerFrom;
    }

    /**
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendEmail(): void
    {
        try {
            $email = new Email();
            $message = $email
                ->to($this->mailerTo)
                ->from($this->mailerFrom)
                ->subject('Data Successfully loaded')
                ->text('Data has been loaded successfully from fruityvice.com');

            $this->mailer->send($message);
        } catch (\Exception $exception) {
            throw new MailerException($exception->getMessage());
        }
    }
}
