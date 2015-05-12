<?php namespace Agency\RealTime;

use Illuminate\Support\ServiceProvider;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class RealTimeServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(
            'Agency\Contracts\Validators\CommentsValidatorInterface',
            'Agency\Validators\CommentsValidator'
        );

        $this->app->bind(
            'Agency\Contracts\Validators\ContentValidatorInterface',
            'Agency\Validators\ContentValidator'
        );

        $this->app->bind(
            'Agency\Contracts\Services\CommentsServiceInterface',
            'Agency\Services\CommentsService'
        );

        $this->app->singleton(
            'Agency\Contracts\Caching\CommentsCacheInterface',
            'Agency\Caching\CommentsCache'
        );

        $this->app->singleton(
            'Agency\Contracts\RealTime\AuthInterface',
            'Agency\RealTime\Auth\Auth'
        );
    }
}
