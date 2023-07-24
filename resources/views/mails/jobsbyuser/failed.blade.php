@component('mail::message')
# {{$job->type->value}} {{$job->status->value}}

Hi {{ $job->user->customer->name }},

There was an error processing your {{$job->type->value}}. Please try again later.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
