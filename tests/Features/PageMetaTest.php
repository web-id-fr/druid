<?php

uses(\Webid\Druid\Tests\Helpers\ApiHelpers::class);

uses(\Webid\Druid\Tests\Helpers\PageCreator::class);

uses(\Webid\Druid\Tests\Helpers\SeoHelpers::class);

test('page meta show up in head section', function () {
    $page = $this->createPage();
    $response = $this->get($page->url());

    $response->assertOk();

    $html = $response->getContent();

    $this->assertStringContainsString(
        "<title>" . $page->meta_title . " - Laravel</title>",
        $html
    );

    $this->assertStringContainsString('<meta name="description" content="' . $page->meta_description . '">', $html);
    $this->assertStringContainsString('<meta name="keywords" content="' . $page->meta_keywords . '">', $html);

    $this->assertStringContainsString('<link rel="canonical" href="' . $page->url() . '"/>', $html);

    $this->assertStringContainsString('<meta property="og:title" content="' . $page->opengraph_title . '">', $html);
    $this->assertStringContainsString('<meta property="og:description" content="' . $page->opengraph_description . '">', $html);
    $this->assertStringContainsString('<meta property="og:type" content="website">', $html);
    $this->assertStringContainsString('<meta property="og:url" content="' . $page->url() . '">', $html);
});

test('page meta show up with resource in api mode', function () {
    $page = $this->createPage();

    $this->enableApiMode();

    $this->get($page->url())
        ->assertJsonFragment([
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,
            'opengraph_title' => $page->opengraph_title,
            'opengraph_description' => $page->opengraph_description,
            'canonical' => $page->url(),
        ]);
});

test('when meta title is not set, then the page title is used', function () {
    $page = $this->createPage(['meta_title' => null]);

    $this->enableApiMode();

    $this->get($page->url())
        ->assertJsonFragment([
            'meta_title' => $page->title,
        ]);
});

test('robots meta reflects page settings only if not disabled from global config', function () {
    $classicPage = $this->createPage();
    $noSeoPage = $this->createPage(['disable_indexation' => true]);

    $response = $this->get($classicPage->url());
    $html = $response->getContent();

    $this->assertStringContainsString('<meta name="robots" content="index,follow">', $html);

    $response = $this->get($noSeoPage->url());
    $html = $response->getContent();

    $this->assertStringContainsString('<meta name="robots" content="nofollow,noindex">', $html);

    $this->globalDisableIndexation();

    $response = $this->get($classicPage->url());
    $html = $response->getContent();

    $this->assertStringContainsString('<meta name="robots" content="nofollow,noindex">', $html);
});

