<?php

use Webid\Druid\Enums\Langs;

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PageCreator::class);

uses(\Webid\Druid\Tests\Helpers\PostCreator::class);

const SWITCHER_ROUTE_NAME = 'switch_lang';

beforeEach(function () {
    $this->enableMultilingualFeature();
    $this->setLocalesList();
});

test('language switcher shows the list of locales in the same order as config file', function () {
    $links = $this->getLanguageSwitcher()->getLinks();

    expect($links)->toHaveCount(3)
        ->and(Langs::EN)->toEqual($links->first())
        ->and(Langs::FR)->toEqual($links->get(1))
        ->and(Langs::DE)->toEqual($links->get(2));
});

test('user can update locale with switch to the same page in other lang', function () {
    $pageSlug = 'page-slug';
    $pageInEnglish = $this->createPageInEnglish(['slug' => $pageSlug]);
    $pageInFrench = $this->createFrenchTranslationPage(['slug' => $pageSlug]);

    expect($pageSlug)->toEqual($pageInEnglish->slug)
        ->and($pageSlug)->toEqual($pageInFrench->slug);

    $this->from($pageInEnglish->url())
        ->get(route(SWITCHER_ROUTE_NAME, [
            'locale' => Langs::FR->value,
        ]))
        ->assertRedirect($pageInFrench->url());
});

test('user is redirect to homepage if the same page does not exist in selected lang', function () {
    $pageSlug = 'page-slug';
    $lang = Langs::FR->value;
    $pageInEnglish = $this->createPageInEnglish(['slug' => $pageSlug]);
    $pageInGerman = $this->createGermanTranslationPage(['slug' => $pageSlug]);

    $this->from($pageInEnglish->url())
        ->get(route(SWITCHER_ROUTE_NAME, [
            'locale' => $lang,
        ]))
        ->assertRedirect("{$lang}/");
});

test('user can update locale with switch to the same post in other lang', function () {
    $postSlug = 'post-slug';
    $postInEnglish = $this->createPostInEnglish(['slug' => $postSlug]);
    $postInFrench = $this->createFrenchTranslationPost(['slug' => $postSlug]);

    expect($postSlug)->toEqual($postInEnglish->slug);
    expect($postSlug)->toEqual($postInFrench->slug);

    $this->from($postInEnglish->url())
        ->get(route(SWITCHER_ROUTE_NAME, [
            'locale' => Langs::FR->value,
        ]))
        ->assertRedirect($postInFrench->url());
});

test('user is redirect to homepage if the same post does not exist in selected lang', function () {
    $lang = Langs::FR->value;
    $postSlug = 'post-slug';
    $postInEnglish = $this->createPostInEnglish(['slug' => $postSlug]);
    $postInGerman = $this->createGermanTranslationPost(['slug' => $postSlug]);

    $this->from($postInEnglish->url())
        ->get(route(SWITCHER_ROUTE_NAME, [
            'locale' => $lang,
        ]))
        ->assertRedirect("{$lang}/");
});
