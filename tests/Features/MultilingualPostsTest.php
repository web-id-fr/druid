<?php

namespace Webid\Druid\Tests\Features;

use Illuminate\Database\UniqueConstraintViolationException;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\Tests\Helpers\ApiHelpers;
use Webid\Druid\Tests\Helpers\DummyUserCreator;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;
use Webid\Druid\Tests\Helpers\PostCreator;
use Webid\Druid\Tests\TestCase;

class MultilingualPostsTest extends TestCase
{
    use ApiHelpers;
    use DummyUserCreator;
    use MultilingualHelpers;
    use PostCreator;

    public function setUp(): void
    {
        parent::setUp();

        $this->disableMultilingualFeature();
    }

    /** @test */
    public function current_language_shows_up_in_url_when_multilingual_feature_is_enabled(): void
    {
        $post = $this->createPostInEnglish();

        $this->assertFalse(isMultilingualEnabled());
        $this->assertEquals($post->url(), url('/blog/'.$post->slug));

        $this->enableMultilingualFeature();

        $this->assertEquals($post->url(), url('/en/blog/'.$post->slug));
    }

    /** @test */
    public function post_can_be_accessible_in_other_language_than_the_default_one(): void
    {
        $this->enableMultilingualFeature();
        $post = $this->createFrenchTranslationPost();

        $this->assertEquals($post->url(), url('/fr/blog/'.$post->slug));

        $this->get($post->url())
            ->assertOk();
    }

    /** @test */
    public function post_url_without_lang_leads_to_a_404(): void
    {
        $this->disableMultilingualFeature();

        $post = $this->createPost(['lang' => null]);
        $this->assertNull($post->lang);
        $postUrlWithoutLang = $post->url();

        $this->get($postUrlWithoutLang)
            ->assertOk();

        $this->enableMultilingualFeature();

        $this->get($postUrlWithoutLang)
            ->assertStatus(404);
    }

    /** @test */
    public function two_posts_can_share_the_same_slug_if_not_in_the_same_lang(): void
    {
        $this->enableApiMode();
        $this->enableMultilingualFeature();

        $postSlug = 'post-slug';
        $postInEnglish = $this->createPostInEnglish(['slug' => $postSlug]);
        $postInFrench = $this->createFrenchTranslationPost(['slug' => $postSlug]);

        $this->assertEquals($postInEnglish->lang, Langs::EN);
        $this->assertEquals($postInFrench->lang, Langs::FR);
        $this->assertEquals($postInEnglish->slug, $postSlug);
        $this->assertEquals($postInFrench->slug, $postSlug);

        $this->get($postInEnglish->url())->assertJsonFragment(['id' => $postInEnglish->getKey()]);
        $this->get($postInEnglish->url())->assertJsonFragment(['lang' => Langs::EN->value]);
        $this->get($postInFrench->url())->assertJsonFragment(['id' => $postInFrench->getKey()]);
        $this->get($postInFrench->url())->assertJsonFragment(['lang' => Langs::FR->value]);
    }

    /** @test */
    public function two_posts_cannot_share_the_same_slug_and_lang(): void
    {
        $this->enableMultilingualFeature();

        $postSlug = 'post-slug';
        $this->createPostInEnglish(['slug' => $postSlug]);

        $this->expectException(UniqueConstraintViolationException::class);
        $this->createPostInEnglish(['slug' => $postSlug]);
    }

    /** @test */
    public function a_post_can_have_translations(): void
    {
        $this->enableMultilingualFeature();

        $originPost = $this->createPostInEnglish();

        $this->assertEmpty($originPost->translations);

        $frenchTranslation = $this->createFrenchTranslationPost(fromPost: $originPost);
        $originPost->refresh();

        $this->assertCount(1, $originPost->translations);
        $this->assertTrue($originPost->translations->first()->is($frenchTranslation));

        $this->createGermanTranslationPost(fromPost: $originPost);
        $originPost->refresh();

        $this->assertCount(2, $originPost->translations);
    }

    /** @test */
    public function multilingual_items_show_up_in_admin_posts_list_when_multilingual_feature_is_enabled(): void
    {
        $user = $this->createDummyUser();
        $this->disableMultilingualFeature();
        $this->createPostInEnglish();

        $this->actingAs($user)
            ->get(route('filament.admin.resources.posts.index'))
            ->assertDontSee('Translations')
            ->assertDontSee('English');
        $this->enableMultilingualFeature();

        $this->actingAs($user)
            ->get(route('filament.admin.resources.posts.index'))
            ->assertSee('Translations')
            ->assertSee('English');
    }
}
