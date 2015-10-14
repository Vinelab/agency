<?php namespace Agency\Content;

use DateTime;
use Carbon\Carbon;
use Agency\Content;

/**
 * This trait provides methods for managing the publishing state
 * of the using classes, assumingly content models.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
trait ScheduledPublishing
{
    public function isPublished()
    {
        return $this->publish_state == Content::STATE_PUBLISHED;
    }

    public function publishDate()
    {
        $date = $this->publish_date;

        if ($date instanceof Carbon || $date instanceof DateTime) {
            $date = $date->format($this->getDateFormat());
        }

        return $date;
    }

    public function publishDateInstance()
    {
        $date = $this->publish_date;

        if (! $date instanceof Carbon) {
            $date = Carbon::createFromFormat($this->getDateFormat(), $date);
        }

        return $date;
    }

    public function getNormalizedPublishDate()
    {
        return (string) str_replace(['-', ' ', ':', '.'], '', $this->publishDate());
    }

    public function shouldBePublished()
    {
        return Carbon::now()->lte($this->publishDateInstance());
    }
}
