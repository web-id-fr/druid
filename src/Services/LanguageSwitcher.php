<?php

namespace Webid\Druid\Services;

use Illuminate\Support\Collection;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;

class LanguageSwitcher
{
    /**
     * @return Collection<(int|string), mixed>
     */
    public function getLinks(): Collection
    {
        $links = collect();
        foreach (Druid::getLocales() as $locale => $details) {
            $links->push(Langs::from($locale));
        }

        return $links;
    }
}
