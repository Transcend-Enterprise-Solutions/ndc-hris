@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="/images/nyclogo.png" class="logo" alt="NYC Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
