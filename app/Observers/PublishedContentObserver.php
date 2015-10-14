<?php namespace Agency\Observers;

use Editorial;

/**
 * Watches models for being published so that they get added to/removed
 * from the published list of content.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class PublishedContentObserver
{
    public function saved($model)
    {
        ($model->isPublished()) ? Editorial::publish($model) : Editorial::unPublish($model);
    }
}
