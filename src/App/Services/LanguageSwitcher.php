<?php

namespace Webid\Druid\App\Services;

use Illuminate\Support\Collection;
use Webid\Druid\App\Dto\LangLink;
use Webid\Druid\App\Enums\Langs;

class LanguageSwitcher
{
    /**
     * @return Collection<LangLink>
     */
    public function getLinks(): Collection
    {
        $links = collect();
        foreach (getLocales() as $locale => $details) {
            $links->push(Langs::from($locale));
        }

        return $links;
    }
}
