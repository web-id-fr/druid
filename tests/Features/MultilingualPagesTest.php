<?php

use Illuminate\Database\UniqueConstraintViolationException;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\DummyUserCreator::class);

uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PageCreator::class);

beforeEach(function () {
    $this->disableMultilingualFeature();
});

test('current language shows up in url when multilingual feature is enabled', function () {
    $page = $this->createPageInEnglish();

    expect(Druid::isMultilingualEnabled())->toBeFalse()
        ->and(url($page->slug))->toEqual($page->url());

    $this->enableMultilingualFeature();

    expect(url('/en/'.$page->slug))->toEqual($page->url());
});

test('page can be accessible in other language than the default one', function () {
    $this->enableMultilingualFeature();
    $page = $this->createFrenchTranslationPage();

    expect(url('/fr/'.$page->slug))->toEqual($page->url());

    $this->get($page->url())
        ->assertOk();
});

test('page url without lang leads to a 404', function () {
    $page = $this->createPage(['lang' => null]);
    $pageUrlWithoutLang = $page->url();
    expect($page->lang)->toBeNull();

    $this->get($pageUrlWithoutLang)
        ->assertOk();

    $this->enableMultilingualFeature();

    $this->get($pageUrlWithoutLang)
        ->assertStatus(404);
});

test('two pages can share the same slug if not in the same lang', function () {
    $this->enableApiMode();
    $this->enableMultilingualFeature();

    $pageSlug = 'page-slug';
    $pageInEnglish = $this->createPageInEnglish(['slug' => $pageSlug]);
    $pageInFrench = $this->createFrenchTranslationPage(['slug' => $pageSlug]);

    expect($pageSlug)->toEqual($pageInEnglish->slug)
        ->and($pageSlug)->toEqual($pageInFrench->slug);

    $this->get($pageInEnglish->url())->assertJsonFragment(['id' => $pageInEnglish->getKey()]);
    $this->get($pageInEnglish->url())->assertJsonFragment(['lang' => Langs::EN->value]);
    $this->get($pageInFrench->url())->assertJsonFragment(['id' => $pageInFrench->getKey()]);
    $this->get($pageInFrench->url())->assertJsonFragment(['lang' => Langs::FR->value]);
});

test('two pages cannot share the same slug and lang', function () {
    $this->enableMultilingualFeature();

    $pageSlug = 'page-slug';
    $this->createPageInEnglish(['slug' => $pageSlug]);

    $this->expectException(UniqueConstraintViolationException::class);
    $this->createPageInEnglish(['slug' => $pageSlug]);
});

test('a page can have translations', function () {
    $this->enableMultilingualFeature();

    $originPage = $this->createPageInEnglish();

    expect($originPage->translations)->toHaveCount(1);

    $frenchTranslation = $this->createFrenchTranslationPage(fromPage: $originPage);
    $originPage->refresh();

    expect($originPage->translations)->toHaveCount(2)
        ->and($originPage->translations->last()->is($frenchTranslation))->toBeTrue();

    $this->createGermanTranslationPage(fromPage: $originPage);
    $originPage->refresh();

    expect($originPage->translations)->toHaveCount(3);
});

test('we are not redirected when accessing page by its slug and has homepage as parent', function () {
    $this->enableMultilingualFeature();

    $homepage = $this->createFrenchTranslationPage([
        'slug' => 'index',
    ]);
    $this->createFrenchTranslationPage([
        'slug' => 'ma-page',
        'parent_page_id' => $homepage->id,
    ]);

    $this->get('/fr/ma-page')
        ->assertOk();

    $this->get('/fr/index/ma-page')
        ->assertRedirect('/fr/ma-page');
});

test('draft post preview is only allowed to logged users', function () {
    $user = $this->createDummyUser();

    $post = $this->createDraftPage();

    $this->get($post->url())
        ->assertStatus(403);

    $this->actingAs($user)
        ->get($post->url())
        ->assertOk();
});

test('we cannot access to the page with incorrect lang parameter', function () {
    $this->enableMultilingualFeature();

    $this->createFrenchTranslationPage([
        'slug' => 'fr-slug',
    ]);

    $this->get('it/fr-slug')->assertNotFound();
});

test('we are redirected if we access to child page with only its slug', function () {
    $this->enableMultilingualFeature();

    $grandParentPage = $this->createFrenchTranslationPage([
        'slug' => 'grand-parent',
    ]);
    $parentPage = $this->createFrenchTranslationPage([
        'slug' => 'parent',
        'parent_page_id' => $grandParentPage->id,
    ]);
    $childPage = $this->createFrenchTranslationPage([
        'slug' => 'child',
        'parent_page_id' => $parentPage->id,
    ]);

    $this->get('/fr/child')
        ->assertRedirect('/fr/grand-parent/parent/child');

    $this->get('/fr/parent/child')
        ->assertRedirect('/fr/grand-parent/parent/child');

    $this->get('/fr/grand-parent/parent/child')
        ->assertOk();
});
