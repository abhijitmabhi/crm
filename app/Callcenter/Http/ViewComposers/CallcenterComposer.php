<?php

namespace LocalheroPortal\Callcenter\Http\ViewComposers;

use Illuminate\View\View;

class CallcenterComposer
{
    public function compose(View $view)
    {
        $view->with('mail', $this->get_email_tamplate());
    }

    protected function get_email_tamplate()
    {
        $emailTemplate = <<<HTML
Sehr geehrter Herr Mustermann,

vielen Dank für das angenehme Telefonat.

Hiermit bestätigen wir Ihnen den Termin in Ihrem Haus am:

- am 04.04.2018
- um 10:00 Uhr - 11:00 Uhr
- in der Evinger Straße1/Ecke Burgweg

Unser Berater in der Digitalisierung Herr Mustermann freut sich auf das persönliche Kennenlernen und einen konstruktiven Gedankenaustausch in entspannter Atmosphäre.

Thema: Die neuesten Technologien in der Digitalisierung (Online) – hat nichts mit Ihrer Website zu tun.

Lesen sie auf unserem Unternehmensprofil Bewertungen von unseren Kunden aus allen Branchen in Deutschland – jetzt hier klicken

Sie werden begeistert sein!
HTML;

        return rawurlencode($emailTemplate);
    }
}
