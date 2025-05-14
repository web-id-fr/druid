<?php

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PostCreator::class);

uses(\Webid\Druid\Tests\Helpers\SeoHelpers::class);
uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

test('post meta show up in head section', function () {
    $this->disableMultilingualFeature();
    $post = $this->createPost();
    $response = $this->get($post->url());

    $response->assertOk();

    $html = $response->getContent();

    $this->assertStringContainsString(
        '<title>'.$post->meta_title.' - Laravel</title>',
        $html
    );

    $this->assertStringContainsString('<meta name="description" content="'.$post->meta_description.'">', $html);
    $this->assertStringContainsString('<meta name="keywords" content="'.$post->meta_keywords.'">', $html);

    $this->assertStringContainsString('<link rel="canonical" href="'.$post->url().'"/>', $html);

    $this->assertStringContainsString('<meta property="og:title" content="'.$post->opengraph_title.'">', $html);
    $this->assertStringContainsString('<meta property="og:description" content="'.$post->opengraph_description.'">', $html);
    $this->assertStringContainsString('<meta property="og:type" content="website">', $html);
    $this->assertStringContainsString('<meta property="og:url" content="'.$post->url().'">', $html);
});

test('post meta show up with resource in api mode', function () {
    $this->disableMultilingualFeature();
    $post = $this->createPost();

    $this->enableApiMode();

    $this->get($post->url())
        ->assertJsonFragment([
            'meta_title' => $post->meta_title,
            'meta_description' => $post->meta_description,
            'meta_keywords' => $post->meta_keywords,
            'opengraph_title' => $post->opengraph_title,
            'opengraph_description' => $post->opengraph_description,
            'canonical' => $post->url(),
        ]);
});

test('when meta title is not set, then the post title is used', function () {
    $this->disableMultilingualFeature();
    $post = $this->createPost(['meta_title' => null]);

    $this->enableApiMode();

    $this->get($post->url())
        ->assertJsonFragment([
            'meta_title' => $post->title,
        ]);
});

test('robots meta reflects post settings only if not disabled from global config', function () {
    $this->disableMultilingualFeature();
    $classicPost = $this->createPost();
    $noSeoPost = $this->createPost(['disable_indexation' => true]);

    $response = $this->get($classicPost->url());
    $html = $response->getContent();

    $this->assertStringContainsString('<meta name="robots" content="index,follow">', $html);

    $response = $this->get($noSeoPost->url());
    $html = $response->getContent();

    $this->assertStringContainsString('<meta name="robots" content="nofollow,noindex">', $html);

    $this->globalDisableIndexation();

    $response = $this->get($classicPost->url());
    $html = $response->getContent();

    $this->assertStringContainsString('<meta name="robots" content="nofollow,noindex">', $html);
});
