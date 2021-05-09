@component('mail::message')

Es wurde soeben ein neuer Standort freigeschaltet:<br/>

{{$location->name}}<br>
{{$location->address}}<br>
{{$location->postcode}} {{$location->city}}
<br/>
<br/>
Dein LocalHero Team.
@endcomponent