@component('mail::message')
# {{$job->type->value}} {{$job->status->value}}

Hi {{ $job->user->customer->name }},

{{ $content }}

@component('mail::button', ['url' => $url])
View {{$job->type->value}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
