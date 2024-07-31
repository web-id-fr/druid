<?php

use Illuminate\Database\UniqueConstraintViolationException;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\DummyUserCreator::class);

uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PostCreator::class);

beforeEach(function () {
    $this->disableMultilingualFeature();
});

function isMultilingualEnabled()
{
}

test('current language shows up in url when multilingual feature is enabled', function () {
    $post = $this->createPostInEnglish();

    expect(Druid::isMultilingualEnabled())->toBeFalse()
        ->and(url('/blog/'.$post->slug))->toEqual($post->url());

    $this->enableMultilingualFeature();

    expect(url('/en/blog/'.$post->slug))->toEqual($post->url());
});

test('post can be accessible in other language than the default one', function () {
    $this->enableMultilingualFeature();
    $post = $this->createFrenchTranslationPost();

    expect(url('/fr/blog/'.$post->slug))->toEqual($post->url());

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

test('two posts can share the same slug if not in the same lang', function () {
    $this->enableApiMode();
    $this->enableMultilingualFeature();

    $postSlug = 'post-slug';
    $postInEnglish = $this->createPostInEnglish(['slug' => $postSlug]);
    $postInFrench = $this->createFrenchTranslationPost(['slug' => $postSlug]);

    expect(Langs::EN)->toEqual($postInEnglish->lang)
        ->and(Langs::FR)->toEqual($postInFrench->lang)
        ->and($postSlug)->toEqual($postInEnglish->slug)
        ->and($postSlug)->toEqual($postInFrench->slug);

    $this->get($postInEnglish->url())->assertJsonFragment(['id' => $postInEnglish->getKey()]);
    $this->get($postInEnglish->url())->assertJsonFragment(['lang' => Langs::EN->value]);
    $this->get($postInFrench->url())->assertJsonFragment(['id' => $postInFrench->getKey()]);
    $this->get($postInFrench->url())->assertJsonFragment(['lang' => Langs::FR->value]);
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

    $frenchTranslation = $this->createFrenchTranslationPost(fromPost: $originPost);
    $originPost->refresh();

    expect($originPost->translations)->toHaveCount(1)
        ->and($originPost->translations->first()->is($frenchTranslation))->toBeTrue();

    $this->createGermanTranslationPost(fromPost: $originPost);
    $originPost->refresh();

    expect($originPost->translations)->toHaveCount(2);
});
