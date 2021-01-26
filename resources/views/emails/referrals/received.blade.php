@component('mail::message')
# {{ $sender->name }} has invited you to sign up to mathsnippets.

Come join other maths tutors and start writing math tutorials.

@component('mail::button', ['url' => "http://localhost:3000/auth/register?referral={$referral->token}"])
Sign Up
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
