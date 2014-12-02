<?php namespace Agency\Tests\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

 use Str, TestCase, Mockery as M, Config;
 use Agency\Repositories\PostRepository;
 use Agency\Contracts\HelperInterface;


class PostRepositoryTest extends TestCase {

    public function __construct()
    {
        $this->mock = M::mock('NeoEloquent');
    }

    public function setUp()
    {
        parent::setUp();

        $this->mPost = M::mock('Agency\Post');
        $this->mImage = M::mock('Agency\Contracts\ImageInterface');
        $this->mVideo = M::mock('Agency\Contracts\VideoInterface');
        $this->images = M::mock('Agency\Contracts\Repositories\ImageRepositoryInterface');
        $this->sections = M::mock('Agency\Contracts\Office\Repositories\SectionRepositoryInterface');
        $this->mHelper = M::mock('Agency\Contracts\HelperInterface');
        $this->mPaginator = M::mock('Starac\Helper\Contracts\PaginatorInterface');

        $this->posts = new PostRepository(  $this->mPost,
                                            $this->sections,
                                            $this->mHelper);
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function test_posts_provider_binding()
    {
        $posts = $this->app->make('Agency\Contracts\Repositories\PostRepositoryInterface');
        $this->assertInstanceOf('Agency\Repositories\PostRepository', $posts);
    }

    public function test_creating_post()
    {
        $title         = 'This Is Going To Be Fun!';
        $slug          = Str::slug($title);
        $body          = 'It is toes touch toes of a mirror image, the bottom half of this image is cut in half.';
        $admin_id      = 1;
        $section_id    = 10;
        $publish_date  = date(time());
        $publish_state = 'scheduled';

        $this->mPost->shouldReceive('create')
            ->with(compact('title', 'slug', 'body', 'admin_id', 'section_id', 'publish_date', 'publish_state'))
            ->andReturn($this->mPost);
        $post = $this->posts->create($title, $slug, $body, $admin_id, $section_id, $publish_date, $publish_state);
        $this->assertInstanceOf('Agency\Post', $post);
    }

    public function test_updating_post()
    {
        $id            = 'pee-id';
        $title         = 'This Is Going To Be Fun!';
        $slug          = Str::slug($title);
        $body          = 'It is toes touch toes of a mirror image, the bottom half of this image is cut in half.';
        $admin_id      = 1;
        $section_id    = 10;
        $publish_date  = date(time());
        $publish_state = 'scheduled';
        $featured = true;

        $this->mPost->shouldReceive('findOrFail')
                ->with($id)->andReturn($this->mPost)

            ->shouldReceive('fill')
                ->with(compact('title', 'slug', 'body','featured', 'publish_date', 'publish_state'))
                ->andReturn($this->mPost)

            ->shouldReceive('save')->withNoArgs()->andReturn(true);

        $post = $this->posts->update($id, $title, $slug, $body, $featured, $publish_date, $publish_state);

        $this->assertInstanceOf('Agency\Post', $post);
    }

    public function test_returns_null_when_not_updated()
    {
        $id            = 'pee-id';
        $title         = 'This Is Going To Be Fun!';
        $slug          = Str::slug($title);
        $body          = 'It is toes touch toes of a mirror image, the bottom half of this image is cut in half.';
        $admin_id      = 1;
        $section_id    = 10;
        $publish_date  = date(time());
        $publish_state = 'scheduled';
        $featured = true;

        $this->mPost->shouldReceive('findOrFail')
                ->with($id)->andReturn($this->mPost)

            ->shouldReceive('fill')
                ->with(compact('title', 'slug', 'body', 'featured', 'publish_date', 'publish_state'))
                ->andReturn($this->mPost)

            ->shouldReceive('save')
                ->withNoArgs()->andReturn(false);

        $post = $this->posts->update($id, $title, $slug, $body, $featured, $publish_date, $publish_state);

        $this->assertNull($post);
    }

    public function test_fetching_posts_by_multiple_id()
    {
        $ids = [1,2,3,4,5];
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->shouldReceive('with')->with('media')->andReturn($this->mPost)
            ->shouldReceive('whereIn')->with($ids)->andReturn($this->mPost)
            ->shouldReceive('get')->withNoArgs()->andReturn($coll);

        $posts = $this->posts->get($ids);
        $this->assertEquals($coll, $posts);
    }

    public function test_removing_post_by_id()
    {
        $id = 123;

        $mSection = M::mock('Agency\Section');

        $this->mPost->shouldReceive('findOrFail')->with($id)
                ->andReturn($this->mPost)
                ->shouldReceive('section')->andReturn($this->mPost)
                ->shouldReceive('edge')->andReturn($this->mPost)
                ->shouldReceive('getAttribute')->andReturn($this->mPost)
            ->shouldReceive('delete')->withNoArgs()->andReturn(true);

        $this->mPost->section = $mSection;


        $this->assertTrue($this->posts->remove($id));
    }

    public function test_removing_post_by_slug()
    {
        $slug = 'my-slug-is-a-fug';

        $mSection = M::mock('Agency\Section');


        $this->mPost->shouldReceive('findOrFail')
                ->with($slug)->andThrow('Illuminate\Database\Eloquent\ModelNotFoundException')
            ->shouldReceive('where')->with('slug', $slug)->andReturn($this->mPost)
            ->shouldReceive('first')->withNoArgs()->andReturn($this->mPost)
            ->shouldReceive('section')->andReturn($this->mPost)
            ->shouldReceive('edge')->andReturn($this->mPost)
            ->shouldReceive('getAttribute')->andReturn($this->mPost)
            ->shouldReceive('delete')->withNoArgs()->andReturn(true);

        $this->mPost->section = $mSection;


        $this->assertTrue($this->posts->remove($slug));
    }

    public function test_fetching_posts_for_section()
    {
        $id = 'sec-id';
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->shouldReceive('where')
                ->with('section_id', $id)->andReturn($this->mPost)
            ->shouldReceive('get')->withNoArgs()
            ->andReturn($coll);

        $posts = $this->posts->forSection($id);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $posts);
    }

    public function test_getting_uniq_slug()
    {
        $title = 'My tItlE iS BuTTifuLL';
        $slug = 'my-title-is-buttifull';

        $this->mHelper->shouldReceive('slugify')->with($title,$this->mPost)
                        ->andReturn($slug);

        $uniq_slug = $this->posts->uniqSlug($title);

        $this->assertEquals($slug, $uniq_slug);
    }

    public function test_getting_uniq_slug_with_same_existing_slugs()
    {
        $title = 'My tItlE iS BuTTifuLL';
        $slug  = 'my-title-is-buttifull';

        $this->mHelper->shouldReceive('slugify')->with($title,$this->mPost)
                        ->andReturn("$slug-10");

        $uniq_slug = $this->posts->uniqSlug($title);

        $this->assertEquals("$slug-10", $uniq_slug);
    }

    public function test_getting_published_posts_with_no_options()
    {
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->shouldReceive('published')->withNoArgs()
                ->andReturn($this->mPost)
            ->shouldReceive('latest')->with('created_at')->andReturn($this->mPost)
            ->shouldReceive('count')->withNoArgs()->andReturn($this->mPost)
            ->shouldReceive('paginate')->with(Config::get('api.limit'))->andReturn($coll)
            ->shouldReceive('orderBy')->with('publish_date','desc')->andReturn($this->mPost)
            ->shouldReceive('get')->withNoArgs()->andReturn($coll);


        $coll->shouldReceive('count')->andReturn(2);


        $this->mPaginator->shouldReceive('paginate')->with($this->mPost, Config::get('api.limit'), 1)->andReturn($coll);


        $this->mPost->shouldReceive('count')->withNoArgs()->andReturn(2);



        $posts = $this->posts->published()->get();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $posts);
    }

    public function test_getting_published_paginated_posts_with_no_options()
    {
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->shouldReceive('published')->withNoArgs()
                ->andReturn($this->mPost)
            ->shouldReceive('latest')->with('created_at')->andReturn($this->mPost)
            ->shouldReceive('paginate')->with(Config::get('api.limit'))->andReturn($coll)
            ->shouldReceive('get')->withNoArgs()->andReturn($coll)
            ->shouldReceive('orderBy')->with('publish_date','desc')->andReturn($this->mPost)
            ->shouldReceive('select')
            ->with('posts.id as id', 'posts.title as title', 'posts.body as body');

        $this->mPaginator->shouldReceive('paginate')->with($this->mPost, Config::get('api.limit'), 1)->andReturn($coll);


        $this->mPost->shouldReceive('count')->withNoArgs()->andReturn(2);
        $coll->shouldReceive('count')->andReturn(2);



        $posts = $this->posts->paginatedPublishedPost();

        $this->assertEquals($coll, $posts);
    }

    public function test_add_tags()
    {
        $id = 20;

        $tags_ids = [1,2,3];

        $this->mPost->shouldReceive('findOrFail')->with($id)
            ->andReturn($this->mPost)
            ->shouldReceive('tags')->andReturn($this->mPost)
            ->shouldReceive('attach')->with($tags_ids)->andReturn(true);


        $added_tags = $this->posts->addTags($id, $tags_ids);
        $this->assertTrue($added_tags);
    }

    public function test_detach_tags()
    {
        $id = 20;

        $belongs_to_many =M::mock('Illuminate\Database\Eloquent\Relations\BelongsToMany');
        $belongs_to_many->shouldReceive('detach')
                        ->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('tags')
                        ->andReturn($belongs_to_many)
                    ->shouldReceive('getAttribute')->andReturn($this->mPost)
                    ->shouldReceive('lists')->andReturn($this->mPost);

        $mCollection = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->tags = $mCollection ;

        $mCollection->shouldReceive('lists')->with("id")->andReturn($mCollection);


        $detached_tags = $this->posts->detachTags($id);

        $this->assertTrue($detached_tags);
    }

    public function test_detach_images()
    {
        $post_id = 20;
        $image_id = 1;

        $morph_to_many =M::mock('Illuminate\Database\Eloquent\Relations\MorphToMany');
        $morph_to_many  ->shouldReceive('detach')->with($image_id)
                        ->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($post_id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('images')
                        ->andReturn($morph_to_many);

        $detached_images = $this->posts->detachImages($post_id,$image_id);

        $this->assertTrue($detached_images);
    }

    public function test_detach_multiple_images()
    {
        $post_id = 20;
        $images_ids = [1,2,3];

        $morph_to_many =M::mock('Illuminate\Database\Eloquent\Relations\MorphToMany');
        $morph_to_many  ->shouldReceive('detach')->with($images_ids)
                        ->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($post_id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('images')
                        ->andReturn($morph_to_many);

        $detached_images = $this->posts->detachImages($post_id,$images_ids);

        $this->assertTrue($detached_images);
    }

    public function test_detach_video()
    {
        $post_id = 20;
        $video_id = 1;

        $morph_to_many =M::mock('Illuminate\Database\Eloquent\Relations\MorphToMany');
        $morph_to_many->shouldReceive('detach')->with($video_id)
                     ->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($post_id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('videos')
                        ->andReturn($morph_to_many);

        $detached_videos = $this->posts->detachVideos($post_id,$video_id);

        $this->assertTrue($detached_videos);
    }


    public function test_detach_multiple_videos()
    {
        $post_id = 20;
        $videos_ids = [1,2,3];

        $morph_to_many =M::mock('Illuminate\Database\Eloquent\Relations\MorphToMany');
        $morph_to_many->shouldReceive('detach')->with($videos_ids)
                     ->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($post_id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('videos')
                        ->andReturn($morph_to_many);

        $detached_videos = $this->posts->detachVideos($post_id,$videos_ids);

        $this->assertTrue($detached_videos);
    }

    public function test_add_images()
    {
        $this->mImage->id = 1;
        $images = [$this->mImage,$this->mImage];

        $post_id = 1;

        $morph_to_many =M::mock('Illuminate\Database\Eloquent\Relations\MorphToMany');
        $morph_to_many->shouldReceive('saveMany')->with($images)->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($post_id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('images')
                        ->andReturn($morph_to_many);

        $added_images = $this->posts->addImages($post_id,$images);

        $this->assertTrue($added_images);
    }

    public function test_add_multiple_images()
    {
        $this->mImage->id = 1;
        $images = [$this->mImage,$this->mImage];

        $post_id = 1;

        $morph_to_many =M::mock('Illuminate\Database\Eloquent\Relations\MorphToMany');
        $morph_to_many->shouldReceive('saveMany')->with($images)->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($post_id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('images')
                        ->andReturn($morph_to_many);

        $added_images = $this->posts->addImages($post_id,$images);

        $this->assertTrue($added_images);
    }


    public function test_add_videos()
    {
        $post_id = 20;
        $this->mVideo->id = 1;
        $videos = [$this->mVideo,$this->mVideo];

        $morph_to_many = M::mock('Illuminate\Database\Eloquent\Relations\MorphToMany');
        $morph_to_many->shouldReceive('saveMany')->with($videos)->andReturn(true);

        $this->mPost->shouldReceive('findOrFail')->with($post_id)
                        ->andReturn($this->mPost)
                    ->shouldReceive('videos')
                        ->andReturn($morph_to_many);

        $added_videos = $this->posts->addVideos($post_id,$videos);

        $this->assertTrue($added_videos);

    }



}
