@component('mail::message')
    # Hello {{ $invitation->first_names ?? 'Student' }},

    You have been invited to participate in the CPUT Workstudy Program.

    Please click the button below to activate your account and set up your password:

    @component('mail::button', ['url' => route('activate.account', ['token' => $invitation->invitation_token])])
        Activate My Account
    @endcomponent

    *This link is secure and will expire in 48 hours.*

    Regards,<br>
    {{ config('app.name') }}
@endcomponent
