<?php

namespace Webid\Druid\Services;

use Illuminate\Support\Collection;
use Webid\Druid\Facades\Druid;

class LanguageSwitcher
{
    public function __construct(private readonly EnvironmentGuesserService $environmentGuesserService)
    {

    }

    /**
     * @return Collection<(int|string), mixed>
     */
    public function getLinks(): Collection
    {
        $links = collect();
        foreach (Druid::getLocales() as $locale => $details) {
            $links->push([
                'label' => $details['label'],
                'url' => $this->environmentGuesserService->getCurrentUrlForLang($locale)
            ]);
        }

        return $links;
    }
}
