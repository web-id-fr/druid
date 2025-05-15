<?php

use Webid\Druid\Facades\Druid;

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PageCreator::class);

beforeEach(function () {
    $this->disableMultilingualFeature();
});

test('multilingual feature can be enabled and disabled using config', function () {
    expect(Druid::isMultilingualEnabled())->toBeFalse();
    $this->enableMultilingualFeature();
    expect(Druid::isMultilingualEnabled())->toBeTrue();
});

test('default locale can be set using config', function () {
    $this->setDefaultLanguageKey('fr');
    expect('fr')->toEqual(Druid::getDefaultLocale());
    $this->setDefaultLanguageKey('de');
    expect('de')->toEqual(Druid::getDefaultLocale());
});

test('current locale can be found anytime with a fallback value', function () {
    $this->setDefaultLanguageKey('fr');
    expect('fr')->toEqual(Druid::getCurrentLocaleKey());
});
