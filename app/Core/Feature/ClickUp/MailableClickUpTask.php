<?php

namespace LocalheroPortal\Core\Feature\ClickUp;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailableClickUpTask extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(string $subject, array $data, string $template)
    {
        $this->viewData = $data;
        $this->subject = $subject;
        $this->view = $template;
    }

    public function build()
    {
        $templateData = ['mailData' => collect($this->viewData)];
        return $this->subject($this->subject)->view($this->view, $templateData);
    }
}
