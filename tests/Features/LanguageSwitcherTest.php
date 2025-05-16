<?php

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PageCreator::class);

uses(\Webid\Druid\Tests\Helpers\PostCreator::class);

beforeEach(function () {
    $this->enableMultilingualFeature();
    $this->setLocalesList();
});

test('language switcher shows the list of locales in the same order as config file', function () {
    $this->enableMultilingualFeature();
    $page = $this->createPageInEnglish();
    $frTranslation = $this->createFrenchTranslationPage(fromPage: $page);

    $this->assertCount(2, $page->translations);
    expect($page->translation_origin_model_id)->toBe($page->id);
    expect($page->translations->first()->lang)->toBe('en');
    expect($page->translations->skip(1)->first()->lang)->toBe('fr');
    expect($page->translations->where('lang', 'en')->first()->status)->toBe(\Webid\Druid\Enums\PageStatus::PUBLISHED);

    $this->get($page->url())
        ->assertViewHas('languageSwitcher.0.label', 'English')
        ->assertViewHas('languageSwitcher.0.url', $page->url())
        ->assertViewHas('languageSwitcher.1.label', 'Français')
        ->assertViewHas('languageSwitcher.1.url', $frTranslation->url())
        ->assertViewHas('languageSwitcher.2.label', 'German')
        ->assertViewHas('languageSwitcher.2.url', '/de');
});
