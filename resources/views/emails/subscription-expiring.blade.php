@component('mail::message')
# Hello {{ $user->name }},

Your subscription is expiring soon. To ensure uninterrupted service, please renew your plan.

@component('mail::button', ['url' => url('/admin/billing')])
Renew Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
