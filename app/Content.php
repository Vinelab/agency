<?php namespace Agency;

use Agency\Caching\Cacheable;
use Agency\Content\ScheduledPublishing;
use Agency\Observers\CachingObserver;
use Agency\Observers\PublishedContentObserver;
use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Carbon\Carbon;
use NeoEloquent;

/**
 * Class Content will get extended by all the different main content entities
 *
 * @category Entity
 * @package  AblaFahita
 * @author   Abed Halawi <abed.halawi@vinelab.com>
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class Content extends NeoEloquent
{
    use Cacheable;
    use ScheduledPublishing;
    use SoftDeletes;

    const STATE_EDITING = 'editing';
    const STATE_PUBLISHED = 'published';

    protected $label = 'Content';

    protected $fillable = ['title', 'slug', 'slug_former'];

    /**
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('publish_state', '=', 'published')
            ->where('publish_date', '<=', Carbon::now(config('app.timezone'))->toDateTimeString());
    }

    /**
     * @return mixed
     */
    public function admin()
    {
        return $this->belongsTo('Agency\Cms\Admin', 'ADMIN');
    }

    /**
     * @return mixed
     */
    public function section()
    {
        return $this->belongsTo("Agency\Cms\Section", "NEWS");
    }

    /**
     * @return mixed
     */
    public function writer()
    {
        return $this->belongsTo('Agency\Writer', 'WRITER');
    }

    /**
     * @return mixed
     */
    public function comments()
    {
        return $this->hasMany('Agency\Comment', 'ON');
    }

}


Content::observe(new CachingObserver());
Content::observe(new PublishedContentObserver());
