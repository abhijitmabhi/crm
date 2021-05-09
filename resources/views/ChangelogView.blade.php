@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Changelogs</h4>
@endsection

@section('main-content')
    <div class="col-12 col-md-6">
        <div role="tablist">
            <!-- Folgendes Markup anpassen und einfügen:
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v{Version Nr} variant="primary">Version {Version Nr}</b-button>
                <b-collapse id="v{Version Nr}" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h2>{Version Nr}</h2>
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>...</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>...</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            -->
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v213 variant="primary">Version 2.13</b-button>
                <b-collapse id="v213" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Seperates Login deaktivieren von Benuztern</li>
                            <li>Stornieren + Wiedervorlage, Kein Interesse, Blackmail</li>
                            <li>Rewrite der Lead Erstellen Funktion</li>
                            <li>Vorausfüllen des Lead Erstellen Formulars in der Leadmap</li>
                            <li>Nutzer mit Manager Rolle können nun Leads erstellen</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>Webseite Button gefixt</li>
                            <li>Lead Google Url gefixt</li>
                            <li>Nutzer Verwaltung gefixt</li>
                            <li>Terminieren von Blacklist Leads Bug gefixt</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v212 variant="primary">Version 2.12</b-button>
                <b-collapse id="v212" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Pipeline Kofiguration mit Branchen</li>
                            <li>Verbesserte SAM Lead Statistik</li>
                            <li>CallAgent Statistik</li>
                            <li>Suchfeld in Nutzerverwaltung</li>
                            <li>Suchfeld nach Experten in Kalender verbessert</li>
                            <li>Experten Gebiet Einstellung mit PLZ</li>
                            <li>Konflikt Darstellung bei Experten Gebiet Einstellung mit PLZ verbessert</li>
                            <li>Termin Erstellung für private Termine verbessert</li>
                            <li>Update für Kalender Funktion</li>
                            <li>Stornierte Leads werden auf Wiedervorlage gesetzt</li>
                            <li>User Management verbessert</li>
                            <li>Verschobene Termine weiterhin im Kalender anzeigen</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>Wiedervorlagen bei PLZ Funktion</li>
                            <li>Termin Beschreibung bei privaten Terminen</li>
                            <li>Problem mit Call Button behoben</li>
                            <li>SAM Suchfeld bei Kalender gefixt</li>
                            <li>Zugriff von SAMs auf Leads von anderen SAMs blockiert</li>
                            <li>Hotfix für Wiedervorlage</li>
                            <li>Hotfix für Lead Erstellung von SAM</li>
                            <li>Hotfix für verschobene Wiedervorlagen bei der PLZ Funktion</li>
                            <li>Fixed Storno Email Versand</li>
                            <li>Fixed Termin verschieben</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v211 variant="primary">Version 2.11</b-button>
                <b-collapse id="v211" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>SAM Standort und Radius Einschränkung hinzugefügt</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>SAM Standort und Radius Einschränkung hinzugefügt</li>
                            <li>Fix und Migration für Lead Koordinaten</li>
                            <li>Hotfix für CallCenter Agent</li>
                            <li>Verbesserte Dupletten Prüfung bei Lead Import</li>
                            <li>Verbesserter Vertragsabschluss</li>
                            <li>Änderung der Sortierung in Lead Pipeline</li>
                            <li>Änderung der Branchenzeiten</li>
                            <li>Änderung der Priorisierung in Lead Pipeline</li>
                            <li>Anpassung Lead Daten</li>
                            <li>Anpassung Callcenter Pipeline "Nicht Erreicht"</li>
                            <li>Usability Verbesserungen und weiteres Bug Fixing</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v23 variant="primary">Version 2.3</b-button>
                <b-collapse id="v23" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Verbesserte Menüs</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>Grüne Termine</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v22 variant="primary">Version 2.2</b-button>
                <b-collapse id="v22" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Pipline Fix</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>kleinere ui/ux Bugfixes</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v21 variant="primary">Version 2.1</b-button>
                <b-collapse id="v21" accordion="changelog-accordion" role="tabpanel">
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Leads können nun von Google generiert werden</li>
                            <li>ein neues Interface auf dem Dashboard, um Leads zu reparieren</li>
                            <li>Dialer Support</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>Termine, die beim Anlegen des Leads eingegeben wurden, speichern nun korrekt</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v20 variant="primary">Version 2.0</b-button>
                <b-collapse id="v20" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>
                                Die Namen bestehender Leads sind nun änderbar.
                            </li>
                            <li>
                                Leads, die keinem aktiven Experten zugeordnet sind, lassen sich über das Backend neu
                                zuweisen.
                            </li>
                            <li>
                                Die Dauer eines Termines für einen Lead kann jetzt beim anlegen bestimmt werden.
                            </li>
                            <li>
                                Beim Anlegen eines neuen Leads werden ähnliche bereits vorhandene Leads angezeigt um
                                doppelte Einträge zu vermeiden.
                            </li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>
                                Die Fehlermeldung bei falscher Postleitzahl wurde übersetzt.
                            </li>
                            <li>
                                Wenn ein Lead über google Maps angelegt wird und die Straße unbekannt ist, wird nicht
                                mehr undefined als Wert übernommen.
                            </li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v19 variant="primary">Version 1.9</b-button>
                <b-collapse id="v19" accordion="changelog-accordion" role="tabpanel" visible>
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Suchvorschläge bei Sidebar-Suche</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>Termine anlegen/löschen/akzeptieren funktionieren wieder</li>
                            <li>Import von iCal-Daten funktioniert wieder</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v18 variant="primary">Version 1.8</b-button>
                <b-collapse id="v18" accordion="changelog-accordion" role="tabpanel">
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Recalls können vom Callcenter-Supervisor neu zugewiesen werden</li>
                            <li>Eine Liste invalider Leads kann vom Callcenter-Supervisor abgerufen werden</li>
                            <li>Leads ohne Kontakt werden nicht mehr in der Pipeline auftauchen</li>
                            <li>Feld für "wichtige Notizen" zu Lead hinzugefügt</li>
                            <li>Wenn der Lead terminiert wurde, wird es nun angezeigt</li>
                            <li>Es werden nun 20 Suchergebnisse pro Seite angezeigt</li>
                            <li>Suchergebnisse verbessert</li>
                            <li>Es werden nun Ergebnisse für Leads und Kunden ausgegeben</li>
                            <li>Suchfeld behält nun zuletzt eingegebenen Begriff</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>ein Darstellungsfehler von Leads auf der Map</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v17 variant="primary">Version 1.7</b-button>
                <b-collapse id="v17" accordion="changelog-accordion" role="tabpanel">
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>Wiedervorlage-Knopf wird nur noch in der Callcenter-Ansicht ausgegeben</li>
                            <li>Wiedervorlagen werden Agenten auf dem Dashboard angezeigt</li>
                            <li>Wird ein Lead nicht erreicht, verlängert sich der Abstand, bis wieder angerufen wird
                            </li>
                            <li>Experten können sich die ihnen zugewiesen Leads auflisten lassen</li>
                            <li>Experten können Leads auf "Privat" ändern</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
            <div class="rounded mb-2">
                <b-button block href="#" v-b-toggle.v16 variant="primary">Version 1.6</b-button>
                <b-collapse id="v16" accordion="changelog-accordion" role="tabpanel">
                    <div class="col pt-3 pb-3">
                        <h4>Neuerungen</h4>
                        <ul>
                            <li>der Callcenter-Supervisor hat nun eine Liste von Agents zur Verfügung</li>
                            <li>Leads können vom Callcenter-Supervisor neu zugewiesen werden</li>
                            <li>Bei Leads verlinkt der Unternehmensname nun auf eine Google-Suche nach eben jenem</li>
                            <li>bei Leads wird nun immer der genaue Status ausgegeben</li>
                            <li>Beim Anlegen von Leads werden nur noch relevante Kategorien angezeigt</li>
                            <li>Experten sehen nur noch den eigenen Kalender</li>
                            <li>mehrere Ansichten des Kalender wurden überarbeitet</li>
                        </ul>
                        <h4>Fixes</h4>
                        <ul>
                            <li>die Pipeline stürzt nun nicht mehr ab, wenn ein Lead einem gelöschten Experten
                                zugewiesen
                                ist
                            </li>
                            <li>visuelle Fixes für Kalender-Ansichten</li>
                        </ul>
                    </div>
                </b-collapse>
            </div>
        </div>
    </div>
@endsection