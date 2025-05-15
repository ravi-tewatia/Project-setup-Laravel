@component('mail::message')
{!! $details['title'] !!}
<br>
{{-- {{ $details['url'] }} --}}

@empty(!$details['buttonname'])
@component('mail::button', ['url' => $details['url']])
{!! $details['buttonname'] !!}
@endcomponent
@endempty
{!! $details['note'] !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
