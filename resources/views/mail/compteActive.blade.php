@component('mail::message')
# Bonjour !

Ce Message est envoyé automatiquement pour vous informer que votre compte est activé par l'administration du plateform.

Vous pouvez Maintenant se-connecter En cliquant sur e button :  

@component('mail::button', ['url' => $url])
Button
@endcomponent


Lien : 
{{$url}}


Merci Bien !<br>
{{ config('app.name') }}
@endcomponent
