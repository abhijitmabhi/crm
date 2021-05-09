@component('mail::message')
# Guten Tag {{$expert->name}},

Ein neuer Termin wurde für dich vereinbart:

<table>
    <tr>
        <td>Firmenname:</td>
        <td>{{$lead->company_name}}</td>
    </tr>
    <tr>
        <td>Kontakt:</td>
        <td>{{$lead->contact_name}}</td>
    </tr>
    <tr>
        <td>Telefon:</td>
        <td>{{$lead->phone1}}</td>
    </tr>
    <tr>
        <td>Datum:</td>
        <td>{{$lead->closed_until}}</td>
    </tr>
</table>

@component('mail::button', ['url' => route('lead.accept', [$lead]), 'color' => 'success'])
Termin bestätigen
@endcomponent

@component('mail::button', ['url' => route('lead.reject', [$lead]), [$lead]])
Termin ablehnen
@endcomponent

Dein {{ config('app.name') }} Team<br>
@endcomponent