<x-filament-panels::page>
    <x-filament::card>
        <table class="w-full text-sm table-auto">
            <thead>
            <tr>
                <th class="text-left p-2">Titre</th>
                <th class="text-left p-2">Slug</th>
                <th class="text-left p-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($pages as $page)
                @include('druid::filament.pages.pages-row', ['page' => $page, 'level' => 0])
            @endforeach
            </tbody>
        </table>
    </x-filament::card>
</x-filament-panels::page>

