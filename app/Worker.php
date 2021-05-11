<?php

namespace RG;

use Pheanstalk\Pheanstalk;
use RG\Sender;
use RG\Log;

class Worker
{
    private $job;

    private $sender;

    private $log;

    public function __construct()
    {
        $this->log = new Log();

        $this->sender = new Sender();

        $this->worker = Pheanstalk::create($_ENV['QUERY_IP'], $_ENV['QUERY_PORT']);

        $this->worker->watch('email-notification');
    }

    private function task(): Task
    {
        $this->job = $this->worker->reserve();

        return new Task(json_decode($this->job->getData(), true));
    }

    public function work()
    {
        $task = $this->task();

        $this->sender->send($task);

        $this->log->success($task);

        $this->worker->delete($this->job);
    }


}