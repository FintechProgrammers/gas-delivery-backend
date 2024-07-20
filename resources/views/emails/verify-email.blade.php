<x-mail::message>
    # Hello {{ $data['first_name'] }}

    use the token below to veriy your email

    {{ $data['code'] }}

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
