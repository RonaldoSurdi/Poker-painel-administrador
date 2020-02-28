@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
    @if ($level == 'error')
        Ops!
    @else
        Olá!
    @endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
    Saudações, <br>SAINTEC Sistemas
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
    Se tiver problemas ao clicar no botão "{{ $actionText }}",
    Copie e cole o URL abaixo em seu navegador da Web:
    [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
