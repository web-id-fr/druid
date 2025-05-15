<?php

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\App;
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

    $post = $this->createPostWithCategory(['lang' => 'fr'], ['lang' => 'fr']);

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

test('draft post preview is only allowed to post author', function () {
    $postAuthor = $this->createDummyUser();
    $otherUser = $this->createDummyUser();

    $post = $this->createDraftPost(forUser: $postAuthor);

    $this->get($post->url())
        ->assertStatus(403);

    $this->actingAs($otherUser)
        ->get($post->url())
        ->assertForbidden();

    $this->actingAs($postAuthor)
        ->get($post->url())
        ->assertOk();
});

test('two posts can share the same slug if not in the same lang', function () {
    $this->enableApiMode();
    $this->enableMultilingualFeature();

    $postSlug = 'post-slug';
    $categorySlug = 'category-slug';

    $englishPost = $this->createPostWithCategory(['slug' => $postSlug, 'lang' => 'en'], ['slug' => $categorySlug, 'lang' => 'en']);
    $frenchPost = $this->createPostWithCategory(['slug' => $postSlug, 'lang' => 'fr'], ['slug' => $categorySlug, 'lang' => 'fr']);

    expect('en')->toEqual($englishPost->lang)
        ->and('fr')->toEqual($frenchPost->lang)
        ->and($postSlug)->toEqual($englishPost->slug)
        ->and($postSlug)->toEqual($frenchPost->slug);

    $this->get($englishPost->url())->assertJsonFragment(['id' => $englishPost->getKey()]);
    $this->get($englishPost->url())->assertJsonFragment(['lang' => 'en']);

    App::setLocale('fr');

    $this->get($frenchPost->url())->assertJsonFragment(['id' => $frenchPost->getKey()]);
    $this->get($frenchPost->url())->assertJsonFragment(['lang' => 'fr']);
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
