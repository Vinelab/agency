<?php namespace Agency\Tests\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

 use Str, TestCase, Mockery as M;
 use Agency\Repositories\PostRepository;

class PostRepositoryTest extends TestCase {

    public function __construct()
    {
        $this->mock = M::mock('Eloquent');
    }

    public function setUp()
    {
        parent::setUp();

        $this->mPost = M::mock('Agency\Post');
        $this->mImage = M::mock('Agency\Contracts\ImageInterface');
        $this->mVideo = M::mock('Agency\Contracts\VideoInterface');
        $this->images = M::mock('Agency\Repositories\Contracts\ImageRepositoryInterface');
        $this->sections = M::mock('Agency\Repositories\Contracts\SectionRepositoryInterface');

        $this->posts = new PostRepository($this->mPost, $this->images, $this->sections, $this->mImage, $this->mVideo);
    }

    public function test_posts_provider_binding()
    {
        $posts = $this->app->make('Agency\Repositories\Contracts\PostRepositoryInterface');
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

        $this->mPost->shouldReceive('create')->once()
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

        $this->mPost->shouldReceive('findOrFail')->once()
                ->with($id)->andReturn($this->mPost)

            ->shouldReceive('fill')->once()
                ->with(compact('title', 'slug', 'body', 'admin_id', 'section_id', 'publish_date', 'publish_state'))
                ->andReturn($this->mPost)

            ->shouldReceive('save')->once()->withNoArgs()->andReturn(true);

        $post = $this->posts->update($id, $title, $slug, $body, $admin_id, $section_id, $publish_date, $publish_state);

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

        $this->mPost->shouldReceive('findOrFail')->once()
                ->with($id)->andReturn($this->mPost)

            ->shouldReceive('fill')->once()
                ->with(compact('title', 'slug', 'body', 'admin_id', 'section_id', 'publish_date', 'publish_state'))
                ->andReturn($this->mPost)

            ->shouldReceive('save')->once()
                ->withNoArgs()->andReturn(false);

        $post = $this->posts->update($id, $title, $slug, $body, $admin_id, $section_id, $publish_date, $publish_state);

        $this->assertNull($post);
    }

    public function test_fetching_posts_by_multiple_id()
    {
        $ids = [1,2,3,4,5];
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->shouldReceive('with')->once()->with('media')->andReturn($this->mPost)
            ->shouldReceive('whereIn')->once()->with($ids)->andReturn($this->mPost)
            ->shouldReceive('get')->once()->withNoArgs()->andReturn($coll);

        $posts = $this->posts->get($ids);
        $this->assertEquals($coll, $posts);
    }

    public function test_removing_post_by_id()
    {
        $id = 123;

        $this->mPost->shouldReceive('findOrFail')->once()->with($id)
                ->andReturn($this->mPost)
            ->shouldReceive('delete')->times(2)->withNoArgs()->andReturn(true);

        $this->assertTrue($this->posts->remove($id));
    }

    public function test_removing_post_by_slug()
    {
        $slug = 'my-slug-is-a-fug';

        $this->mPost->shouldReceive('findOrFail')->once()
                ->with($slug)->andThrow('Illuminate\Database\Eloquent\ModelNotFoundException')
            ->shouldReceive('where')->once()->with('slug', $slug)->andReturn($this->mPost)
            ->shouldReceive('first')->once()->withNoArgs()->andReturn($this->mPost)
            ->shouldReceive('delete')->once()->withNoArgs()->andReturn(true);

        $this->assertTrue($this->posts->remove($slug));
    }

    public function test_fetching_posts_for_section()
    {
        $id = 'sec-id';
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->shouldReceive('where')->once()
                ->with('section_id', $id)->andReturn($this->mPost)
            ->shouldReceive('get')->once()->withNoArgs()
            ->andReturn($coll);

        $posts = $this->posts->forSection($id);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $posts);
    }

    public function test_getting_uniq_slug()
    {
        $title = 'My tItlE iS BuTTifuLL';
        $slug = 'my-title-is-buttifull';

        $this->mPost->shouldReceive('whereRaw')->once()
                ->with("slug REGEXP '^{$slug}(-[0-9]*)?$'")
                ->andReturn($this->mPost)
            ->shouldReceive('count')->withNoArgs()->once()
            ->andReturn(0);

        $uniq_slug = $this->posts->uniqSlug($title);

        $this->assertEquals($slug, $uniq_slug);
    }

    public function test_getting_uniq_slug_with_same_existing_slugs()
    {
        $title = 'My tItlE iS BuTTifuLL';
        $slug  = 'my-title-is-buttifull';

        $this->mPost->shouldReceive('whereRaw')->once()
                ->with("slug REGEXP '^{$slug}(-[0-9]*)?$'")
                ->andReturn($this->mPost)
            ->shouldReceive('count')->withNoArgs()->once()->andReturn(10);

        $uniq_slug = $this->posts->uniqSlug($title);

        $this->assertEquals("$slug-10", $uniq_slug);
    }

    public function test_getting_published_posts_with_no_options()
    {
        $coll = M::mock('Illuminate\Database\Eloquent\Collection');

        $this->mPost->shouldReceive('published')->once()->withNoArgs()
                ->andReturn($this->mPost)
            ->shouldReceive('latest')->once()->with('created_at')->andReturn($this->mPost)
            ->shouldReceive('paginate')->once()->with(M::type('int'))
            ->shouldReceive('get')->once()->withNoArgs()->andReturn($coll)
            ->shouldReceive('select')->once()
                ->with('posts.id as id', 'posts.title as title', 'posts.body as body');

        $posts = $this->posts->published();

        $this->assertEquals($coll, $posts);
    }
}
