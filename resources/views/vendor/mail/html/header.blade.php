@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <span style="font-size: 24px; font-weight: bold; color: #4f46e5;">
                📝 {{ $slot }}
            </span>
        </a>
    </td>
</tr>