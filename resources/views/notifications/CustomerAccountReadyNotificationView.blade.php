@component('mail::message')

Dein Account ist jetzt bereit!<br/>

## Zugangsdaten:
Login: <a href="portal.localhero.de">portal.localhero.de</a><br/>
Email: {{$user->email}}<br/>
Passwort: {{$password}}<br/>
<br/>
<br/>
Dein LocalHero Team
@endcomponent