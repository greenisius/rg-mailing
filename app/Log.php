<?php


namespace RG;


use RG\Task;
use Sentry;
use Sentry\Severity;
use Sentry\State\Scope;


class Log
{
    private function configure(Task $task)
    {
        Sentry\configureScope(function (Scope $scope) use ($task): void {
            $scope->setTag('destination', $task->destination[0]);
            $scope->setTag('theme', $task->theme);
            $scope->setTag('content', $task->content);
            $scope->setTag('from', $task->sender);
        });
    }

    public function success(Task $task)
    {
        $this->configure($task);

        Sentry\captureMessage('Письмо отправлено', Severity::info());
    }

    public function fail(Task $task)
    {
        $this->configure($task);

        Sentry\captureMessage('Письмо не было отправлено', Severity::error());

    }
}