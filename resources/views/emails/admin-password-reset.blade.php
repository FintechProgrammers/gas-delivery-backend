<x-mail::message>
    # Password Reset Request

    You are receiving this email because we received a password reset request for your account.
    If you did not request a password reset, no further action is required.

    <x-mail::button :url="$url">
        Reset Password
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
