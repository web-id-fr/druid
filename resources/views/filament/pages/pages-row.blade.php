<tr>
    <td class="p-2">
        {{ str_repeat(' - ', $level) }}{{ $page->title }}
    </td>
    <td class="p-2">
        {{ $page->slug }}
    </td>
    <td class="p-2">

    </td>
</tr>
@foreach ($page->children as $child)
    @include('druid::filament.pages.pages-row', ['page' => $child, 'level' => $level + 1])
@endforeach
