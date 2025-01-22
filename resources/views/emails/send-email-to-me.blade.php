<x-mail::message>
    # A Contact Me form was filled up in your website!
    <br /><br />

    <p>Hi Kevin,</p>
    <br />

    <p>You are receiving this email because a form was filled up on your Contact Me page.</p>

    <ul>
        <li>Name: {{ $contact_me->last_name }}, {{ $contact_me->first_name }}</li>
        <li>Email: {{ $contact_me->email }}</li>
        {!! $contact_me->company ? '<li>Company: ' .$contact_me->company. '</li>' : '' !!}
    </ul>

    {!! Markdown::parse($contact_me->message) !!}

    <br />

    ---
    Copyright &copy; 2025
</x-mail::message>
