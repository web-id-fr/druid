<?php

namespace Webid\Druid\Services\Admin\FilamentFieldsBuilders;

use Filament\Forms\Components\Component;

class FilamentFieldsBuilder
{
    /** @var array<int, Component> */
    private array $fields = [];

    public function getFields(): array
    {
        return $this->fields;
    }

    public function updateFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function addField(
        Component $field,
        string $fieldKey,
        ?string $ancestorPath = null,
        ?string $before = null,
        ?string $after = null,
    ): void {
        if (! $ancestorPath) {
            $this->fields[$fieldKey] = $field;

            return;
        }

        $ancestorsKeys = explode('.', $ancestorPath);

        /** @var Component $currentAncestor */
        $currentAncestor = $this->fields[$ancestorsKeys[0]];
        unset($ancestorsKeys[0]);

        foreach ($ancestorsKeys as $ancestorsKey) {
            $ancestors = $currentAncestor->getChildComponents();

            /** @var Component $currentAncestor */
            $currentAncestor = $ancestors[$ancestorsKey];
        }

        $childComponents = $currentAncestor->getChildComponents();
        if ($before || $after) {
            $position = array_search($before ?? $after, array_keys($childComponents));
            if ($after) {
                $position++;
            }

            $currentAncestor->childComponents([
                ...array_slice($childComponents, 0, $position, true),
                $fieldKey => $field,
                ...array_slice($childComponents, $position, null, true),
            ]);

            return;
        }

        $currentAncestor->childComponents([...$currentAncestor->getChildComponents(), $fieldKey => $field]);
    }

    public function removeField(string $fieldKey, ?string $ancestorPath = null): void
    {
        if (! $ancestorPath) {
            unset($this->fields[$fieldKey]);

            return;
        }

        $ancestorsKeys = explode('.', $ancestorPath);

        /** @var Component $currentAncestor */
        $currentAncestor = $this->fields[$ancestorsKeys[0]];
        unset($ancestorsKeys[0]);

        foreach ($ancestorsKeys as $ancestorsKey) {
            $ancestors = $currentAncestor->getChildComponents();

            /** @var Component $currentAncestor */
            $currentAncestor = $ancestors[$ancestorsKey];
        }

        $childComponents = $currentAncestor->getChildComponents();
        unset($childComponents[$fieldKey]);

        $currentAncestor->childComponents($childComponents);
    }
}
