<?php


namespace LocalheroPortal\Core\Feature\ClickUp;


use Illuminate\Support\Facades\Mail;

//TODO: use as laravel service
class ClickUpService
{

    public function createClickUpTask(MailableClickUpTask $task, string $list): void
    {
        Mail::to($list)->send($task);
    }
}