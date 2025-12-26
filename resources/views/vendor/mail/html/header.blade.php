@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'CulturalTranslate')
<img src="{{ asset('images/logo.png') }}" class="logo" alt="Cultural Translate Logo" style="height: 50px;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
