<?php


namespace RG;


use RG\Log;


class Sender
{
    private $message;

    private $mailer;

    private $log;

    public function __construct()
    {
        $this->log = new Log();

        $transport = new \Swift_SmtpTransport($_ENV['EMAIL_TRANSPORT_IP'], $_ENV['EMAIL_TRANSPORT_PORT']);
        $transport
            ->setUsername($_ENV['EMAIL_TRANSPORT_USERNAME'])
            ->setPassword($_ENV['EMAIL_TRANSPORT_PASSWORD']);

        $this->mailer = new \Swift_Mailer($transport);

        $this->message = new \Swift_Message();
    }

    public function send(Task $task)
    {
        foreach ($task->destination as $destination) {

            $taskUnit = self::prepareUnit($task, $destination);

            try {
                $this->message
                    ->setFrom([$taskUnit->from ?? $_ENV['EMAIL_SENDER_ADDRESS'] => $taskUnit->sender ?? $_ENV['EMAIL_SENDER_NAME']])
                    ->setTo($destination)
                    ->setSubject($taskUnit->theme)
                    ->setBody($taskUnit->content)
                    ->getHeaders()
                    ->addTextHeader('List-Unsubscribe', $taskUnit->unsubscribe);
                foreach ($taskUnit->attachment as $path) {
                    $this->message->attach(\Swift_Attachment::fromPath($path));
                }

                $RSA = openssl_get_privatekey($_ENV['MAIL_RSA_PRIV'], $_ENV['MAIL_RSA_PASSPHRASE']);
                $signer = new \Swift_Signers_DKIMSigner($RSA, $_ENV['MAIL_DOMAIN'], $_ENV['MAIL_SELECTOR']);
                $this->message->attachSigner($signer);

                $this->mailer->send($this->message);

            } catch (\Throwable $e) {
                $this->log->fail($taskUnit);
                continue;
            }

            $this->log->success($taskUnit);
        }
    }

    private static function prepareUnit(Task $task, $destination)
    {
        $unit = clone $task;

        $unit->destination = [$destination];

        return $unit;
    }
}