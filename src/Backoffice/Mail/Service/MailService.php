<?php
namespace GameShop\Site\Backoffice\Mail\Service;

/**
 * Class MailService
 * @package GameShop\Site\Backoffice\Mail\Service
 */
class MailService
{
    protected $mailer;

    /**
     * MailService constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param \Swift_Message $message
     */
    public function send(\Swift_Message $message)
    {
        $this->mailer->send($message);
    }
}
