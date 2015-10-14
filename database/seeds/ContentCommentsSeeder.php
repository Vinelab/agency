<?php

use AblaFahita\User;
use AblaFahita\News;
use AblaFahita\Album;
use AblaFahita\Content;
use AblaFahita\Episode;
use AblaFahita\Comment;
use  Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use AblaFahita\RealTime\Content as RealTimeContent;

/**
 * @category Seeder
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class ContentCommentsSeeder extends Seeder
{
    protected $users_count = 30;

    protected $comments_count = [ // per piece of content
        'max' => 100,
        'min' => 3
    ];

    public function run()
    {
        NeoEloquent::unguard();
        $this->fake = Faker::create('ar_JO');
        $this->cache = App::make('AblaFahita\Caching\CommentsCache');
        $this->service = App::make('AblaFahita\Services\CommentsService');

        // users
        $users = $this->seedUsers();
        echo "- Seeded ".$this->users_count." users\n";

        $comments_ids = $this->seedComments($users);
        echo "- Seeded ".count($comments_ids)." comments\n";
    }

    public function seedUsers()
    {
        $users = [];

        for ($i = 0; $i < $this->users_count; $i++) {

            $user = User::create([
                'name'    => $this->fake->name,
                'email'   => $this->fake->email,
                'avatar'  => $this->fake->imageUrl(75, 75),
                'blocked' => $this->fake->boolean(15),
            ]);

            $users[] = $user;
        }

        return $users;
    }

    public function seedComments($users)
    {
        $seeded = [];
        // comment on articles
        foreach (News::get() as $article) {
            $seeded = array_merge($seeded, $this->seedCommentForContent($article, $users));
        }
        // comment on episodes
        foreach (Episode::get() as $episode) {
            if ($episode->live && $episode->behindTheScenes) {
                $seeded = array_merge($seeded, $this->seedCommentForContent($episode->live, $users));
                $seeded = array_merge($seeded, $this->seedCommentForContent($episode->behindTheScenes, $users));
            }
        }
        // comment on albums
        foreach (Album::get() as $album) {
            $seeded = array_merge($seeded, $this->seedCommentForContent($album, $users));
        }

        return $seeded;
    }

    /**
     * Seed a number of comments to the given content.
     *
     * @param \AblaFahita\Content $content
     * @param array $users_ids
     *
     * @return array The ids of seeded comments
     */
    public function seedCommentForContent(Content $content, array $users)
    {
        $count = $this->fake->numberBetween($this->comments_count['min'], $this->comments_count['max']);
        $users_ids = array_map(function($user) { return $user->getKey(); }, $users);

        $seeded = [];
        for ($i = 0; $i < $count; $i++) {
            $comment = Comment::createWith(
                [
                    'text' => $this->fake->realText($this->fake->numberBetween(20, 600)),
                    'spam' => $this->fake->boolean(15),
                ],
                [
                    'content' => $content->getKey(),
                    'user'    => $this->fake->randomElement($users_ids)
                ]
            );

            $cached = $this->cache->add($comment, RealTimeContent::makeFromContent($content));

            if ($comment->spam) {
                $this->cache->spam($comment);
            }

            $likers = $this->fake->randomElements($users, $this->fake->numberBetween(1, count($users_ids)-1));
            foreach ($likers as $liker) {
                $this->service->like($comment->getKey(), $liker);
            }

            $reporters = $this->fake->randomElements($users, $this->fake->numberBetween(1, $this->users_count/2));
            foreach ($reporters as $reporter) {
                $this->service->report($comment->getKey(), $reporter);
            }

            $seeded[] = $comment->getKey();
        }

        return $seeded;
    }
}
