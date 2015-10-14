<?php namespace Agency\Observers;

use App;

/**
 * Adds/updates models in cache when saved.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class CachingObserver
{
    public function saved($model)
    {
        $redis = App::make('redis');
        $redis->hmset($model->getCacheableKey(), $model->toCacheable());
    }
}
