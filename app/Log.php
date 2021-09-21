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
            $scope->setTag('destination', $task->destination[0] ?? '<Невалидный email>');
            $scope->setTag('theme', $task->theme ?? '<Тема не задана>');
            $scope->setTag('content', str_replace("\n", '', $task->content) ?? '<Без содержания>');
            $scope->setTag('from', $task->sender ?? '<Без отправителя>');
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