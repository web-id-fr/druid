<?php

namespace Webid\Druid\Services;

use Illuminate\Support\Collection;
use Webid\Druid\Dto\LangLink;
use Webid\Druid\Enums\Langs;

class LanguageSwitcher
{
    public function __construct()
    {

    }

    /**
     * @return Collection<LangLink>
     */
    public function getLinks(): Collection
    {
        $links = collect();
        foreach (getLocales() as $locale => $details) {
            $homepage = isset($details['homepage']) ? $details['homepage'] : '/';
            $links->push(LangLink::make($homepage, Langs::from($locale)));
        }

        return $links;
    }
}
