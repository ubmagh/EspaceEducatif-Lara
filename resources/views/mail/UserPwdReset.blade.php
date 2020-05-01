@component('mail::message')
# Bonjour !

pour  réinitialiser le mot de passe de votre compte cliquez sur ce botton :

@component('mail::button', ['url' => $url])
Button
@endcomponent

ou Allez sur le lien :

<code>{{$url}}</code>

<small>
    si vous n'avez pas demandé de ce service et de réinitialisation de votre mot de passe, juste supprimez ce message !
</small>

Merci Bien !<br>
{{ config('app.name') }}
@endcomponent
