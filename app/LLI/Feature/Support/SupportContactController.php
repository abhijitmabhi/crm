<?php


namespace LocalheroPortal\LLI\Feature\Support;


use LocalheroPortal\Core\Feature\ClickUp\ClickUpEmailList;
use LocalheroPortal\Core\Feature\ClickUp\ClickUpService;
use LocalheroPortal\Core\Feature\ClickUp\MailableClickUpTask;
use LocalheroPortal\Core\Http\Controllers\Controller;

class SupportContactController extends Controller
{

    public function getView()
    {
        return view('lli.support.SupportContactView');
    }

    public function sendSupportRequest(SupportContactRequest $request)
    {
        $this->createClickUpTask($request);
    }

    private function createClickUpTask($request)
    {
        $taskData = [
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        $clickUpService = new ClickUpService();
        $subject = "Subject \"" . $taskData['subject'] . "\" hat ein Anfrage!";
        $taskTemplate = 'lli.support.SupportContactMailClickUpTemplate';
        $clickUpTask = new MailableClickUpTask($subject, $taskData, $taskTemplate);
        $clickUpService->createClickUpTask($clickUpTask, ClickUpEmailList::SUPPORT);
    }

}