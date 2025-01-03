@php
    use Illuminate\View\ComponentAttributeBag;
@endphp

@props([
    'action',
    'afterItem' => null,
    'blocks',
    'columns' => null,
    'statePath',
    'trigger',
    'width' => null,
])

<div x-data="{ search: '' }">
    <x-filament::dropdown
        :width="$width"
        {{ $attributes->class(['fi-fo-builder-block-picker']) }}
    >
        <x-slot name="trigger">
            {{ $trigger }}
        </x-slot>

        <x-filament::dropdown.list>
            <x-filament::input.wrapper
                inline-prefix
                prefix-icon="heroicon-m-magnifying-glass"
                prefix-icon-alias="tables::search-field"
                style="margin: 3px 3px 10px"
            >
                <x-filament::input
                    :attributes="
                (new ComponentAttributeBag)->merge([
                    'autocomplete' => 'off',
                    'inlinePrefix' => true,
                    'placeholder' => __('Search...'),
                    'type' => 'search',
                    'x-model' => 'search',
                    'x-on:keyup' => 'if ($event.key === \'Enter\') { $wire.$refresh() }',
                ])
            "
                />
            </x-filament::input.wrapper>
            <div class="max-h-96 overflow-y-auto">
                <x-filament::grid
                    :default="$columns['default'] ?? 1"
                    :sm="$columns['sm'] ?? null"
                    :md="$columns['md'] ?? null"
                    :lg="$columns['lg'] ?? null"
                    :xl="$columns['xl'] ?? null"
                    :two-xl="$columns['2xl'] ?? null"
                    direction="column"
                >
                    @foreach ($blocks as $block)
                        @php
                            $wireClickActionArguments = ['block' => $block->getName()];

                            if (filled($afterItem)) {
                                $wireClickActionArguments['afterItem'] = $afterItem;
                            }

                            $wireClickActionArguments = \Illuminate\Support\Js::from($wireClickActionArguments);

                            $wireClickAction = "mountFormComponentAction('{$statePath}', '{$action->getName()}', {$wireClickActionArguments})";
                        @endphp
                        <x-filament::dropdown.list.item
                            x-on:click="close"
                            :wire:click="$wireClickAction"
                            x-show="search === '' || '{{ $block->getLabel() }}'.toLowerCase().includes(search.toLowerCase())"
                        >
                            @if ($block->getIcon())
                                <img src="{{ $block->getIcon() }}" alt="{{ $block->getLabel() }} preview" class="w-100 h-auto" style="max-height: 350px;" />
                            @endif
                            <div class="mt-2 font-bold">
                                {{ $block->getLabel() }}
                            </div>
                        </x-filament::dropdown.list.item>
                    @endforeach
                </x-filament::grid>
            </div>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
