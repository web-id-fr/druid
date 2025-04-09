<?php

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\App;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\DummyUserCreator::class);

uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PostCreator::class);

uses(\Webid\Druid\Tests\Helpers\CategoryCreator::class);

beforeEach(function () {
    $this->disableMultilingualFeature();
});

function isMultilingualEnabled() {}

test('current language shows up in url when multilingual feature is enabled', function () {
    $post = $this->createPostInEnglish();

    expect(Druid::isMultilingualEnabled())->toBeFalse()
        ->and(url('/blog/'.$post->categories->first()->slug.'/'.$post->slug))->toEqual($post->url());

    $this->enableMultilingualFeature();

    expect(url('/en/blog/'.$post->categories->first()->slug.'/'.$post->slug))->toEqual($post->url());
});

test('post can be accessible in other language than the default one', function () {
    $this->enableMultilingualFeature();

    $post = $this->createPostWithCategory(['lang' => Langs::FR->value], ['lang' => Langs::FR->value]);

    expect(url('/fr/blog/'.$post->categories->first()->slug.'/'.$post->slug))->toEqual($post->url());

    $this->get($post->url())
        ->assertOk();
});

test('post url without lang leads to a 404', function () {
    $post = $this->createPost(['lang' => null]);
    expect($post->lang)->toBeNull();
    $postUrlWithoutLang = $post->url();

    $this->get($postUrlWithoutLang)
        ->assertOk();

    $this->enableMultilingualFeature();

    $this->get($postUrlWithoutLang)
        ->assertStatus(404);
});

test('draft post is forbidden to visitors', function () {
    $post = $this->createDraftPost();

    $this->get($post->url())
        ->assertStatus(403);
});

test('two posts can share the same slug if not in the same lang', function () {
    $this->enableApiMode();
    $this->enableMultilingualFeature();

    $postSlug = 'post-slug';
    $categorySlug = 'category-slug';

    $englishPost = $this->createPostWithCategory(['slug' => $postSlug, 'lang' => Langs::EN->value], ['slug' => $categorySlug, 'lang' => Langs::EN->value]);
    $frenchPost = $this->createPostWithCategory(['slug' => $postSlug, 'lang' => Langs::FR->value], ['slug' => $categorySlug, 'lang' => Langs::FR->value]);

    expect(Langs::EN)->toEqual($englishPost->lang)
        ->and(Langs::FR)->toEqual($frenchPost->lang)
        ->and($postSlug)->toEqual($englishPost->slug)
        ->and($postSlug)->toEqual($frenchPost->slug);

    $this->get($englishPost->url())->assertJsonFragment(['id' => $englishPost->getKey()]);
    $this->get($englishPost->url())->assertJsonFragment(['lang' => Langs::EN->value]);

    App::setLocale(Langs::FR->value);

    $this->get($frenchPost->url())->assertJsonFragment(['id' => $frenchPost->getKey()]);
    $this->get($frenchPost->url())->assertJsonFragment(['lang' => Langs::FR->value]);
});

test('two posts cannot share the same slug and lang', function () {
    $this->enableMultilingualFeature();

    $postSlug = 'post-slug';
    $this->createPostInEnglish(['slug' => $postSlug]);

    $this->expectException(UniqueConstraintViolationException::class);
    $this->createPostInEnglish(['slug' => $postSlug]);
});

test('a post can have translations', function () {
    $this->enableMultilingualFeature();

    $originPost = $this->createPostInEnglish();

    expect($originPost->translations)->toBeEmpty();

    $this->createFrenchTranslationPost(fromPost: $originPost);
    $originPost->refresh();

    expect($originPost->translationOriginModel->translations)->toHaveCount(1)
        ->and($originPost->translations->first()->is($originPost))->toBeTrue();

    $this->createGermanTranslationPost(fromPost: $originPost);
    $originPost->refresh();

    expect($originPost->translationOriginModel->translations)->toHaveCount(2);
});
