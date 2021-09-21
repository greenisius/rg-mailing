<?php

namespace RG;

use Pheanstalk\Pheanstalk;
use RG\Sender;

class Worker
{
    private $job;

    private $sender;

    public function __construct()
    {
        $this->sender = new Sender();

        $this->worker = Pheanstalk::create($_ENV['QUERY_IP'], $_ENV['QUERY_PORT']);

        $this->worker->watch('email-notification');
    }

    public function work()
    {
        $task = $this->task();

        if ($task->isBulk()) {
            $this->split($task);
        } else {
            $this->sender->send($task);
        }

        $this->worker->delete($this->job);
    }

    public function add(Task $task)
    {
        $this->worker->useTube('email-notification')->put(json_encode($task));
    }

    private function task(): Task
    {
        $this->job = $this->worker->reserve();

        return new Task(json_decode($this->job->getData(), true));
    }

    private function split($task)
    {
        foreach ($task->destination as $destination) {
            $unit = clone $task;

            $unit->destination = [$destination];

            $this->add($unit);
        }
    }
}