<?php

namespace App\Utils\Mailer;

use App\Utils\Mailer\DTO\MailerOptionsDto;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerSender
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendTemplatedEmail(MailerOptionsDto $mailerOptionsDto): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->to($mailerOptionsDto->getRecipient())
            ->subject($mailerOptionsDto->getSubject())
            ->htmlTemplate($mailerOptionsDto->getHtmlTemplate())
            ->context($mailerOptionsDto->getContext());

        if ($mailerOptionsDto->getCc()) {
            $email->cc($mailerOptionsDto->getCc());
        }

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical($mailerOptionsDto->getSubject(), [
                'errorText' => $e->getTraceAsString()
            ]);
        }

        return $email;
    }
}