<?php

namespace App\Utils\Mailer\DTO;

class MailerOptionsDto
{
    private string $recipient;
    private string|null $cc = null;
    private string $subject;
    private string $htmlTemplate;
    private array $context;
    private string $text;


    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function setCc(string $cc): self
    {
        $this->cc = $cc;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }


    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getHtmlTemplate(): string
    {
        return $this->htmlTemplate;
    }

    public function setHtmlTemplate(string $htmlTemplate): self
    {
        $this->htmlTemplate = $htmlTemplate;

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

}