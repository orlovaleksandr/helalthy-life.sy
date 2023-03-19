<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\Order;
use App\Utils\Mailer\DTO\MailerOptionsDto;
use App\Utils\Mailer\MailerSender;

class OrderCreatedFromCartEmailSender
{
    private MailerSender $mailerSender;

    public function __construct(MailerSender $mailerSender)
    {
        $this->mailerSender = $mailerSender;
    }

    public function sendEmailToClient(Order $order)
    {
        $mailerOptions = (new MailerOptionsDto())
            ->setRecipient($order->getOwner()->getEmail())
            ->setCc('orlov_a@tut.by')
            ->setSubject('Healthy life - Thank you for your purchase!')
            ->setHtmlTemplate('main/email/client/created-order-from-cart.html.twig')
            ->setContext([
                'order' => $order
            ]);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }

    public function sendEmailToManager(Order $order)
    {
        $mailerOptions = (new MailerOptionsDto())
            ->setRecipient('manager@healthy-life.com')
            ->setSubject('Client created an order')
            ->setHtmlTemplate('main/email/manager/created-order-from-cart.html.twig')
            ->setContext([
                'order' => $order
            ]);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}