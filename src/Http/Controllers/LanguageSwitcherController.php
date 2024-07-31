<?php

declare(strict_types=1);

namespace Webid\Druid\Http\Controllers;

use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\SessionManager;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Services\EnvironmentGuesserService;

class LanguageSwitcherController extends Controller
{
    public function __construct(
        private readonly UrlGenerator $url,
        private readonly SessionManager $session,
        private readonly Repository $config,
        private readonly EnvironmentGuesserService $environmentGuesserService
    ) {}

    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        $locale = Langs::tryFrom($locale);
        /** @var string $lang */
        $lang = $locale->value ?? $this->config->get('cms.default_locale');

        $routePath = $this->environmentGuesserService->getEnvironment($this->url->previousPath(), $lang);

        $this->session->put('locale', $lang);

        if ($routePath) {
            return redirect()->to($routePath);
        }

        return redirect()->to("{$lang}/");
    }
}
