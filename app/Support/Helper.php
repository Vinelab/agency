<?php namespace Agency\Support;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */

use Illuminate\Support\Facades\Config;
use Shorten;
use Agency\Contracts\HelperInterface;
use Auth;
use Carbon\Carbon;
use DateTimeZone;

class Helper implements HelperInterface
{

    /**
     * Transforms a camelCase string to
     * snake-case.
     *
     * @param  string $string
     *
     * @return string
     */
    public function aliasify($string)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('-', $ret);
    }

    /**
     * get a list of all the php timezones
     *
     * @return array
     */
    public function getTimezones()
    {
        return DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    }

    /**
     * Transform a normal HTML into
     * a stripped HTML (no tags attributes
     * except the href in the a tags)
     *
     * @param  string $html
     *
     * @return string
     */
    public function cleanHTML($html)
    {
        $text = preg_replace('~</(p|div|h[0-9])>~', '</$1><br />', $html);

        $text = Helper::div2br($text);

        $text = strip_tags($text, '<a><br><b><strike><u><i>');

        $text = Helper::br2nl($text);

        // remove tag attributes except <a>
        $text = preg_replace('~<(?!a\s)([a-z][a-z0-9]*)[^>]*?(/?)>~i', '<$1$2>', $text);
        // remove all attributes from <a> except 'href'
        $text = preg_replace('~<a\s.*(href=.*)>~i', '<a $1>', $text);
        $text = preg_replace('/class=".*?"/', '', $text);
        $text = preg_replace('/style=".*?"/', '', $text);

        return $text;
    }

    /**
     * Convert <br> to \n
     *
     * @param  string $html
     *
     * @return string
     */
    public function br2nl($html)
    {
        return preg_replace('~<\s*br\s*/?>~', "\n", $html);
    }

    /**
     * Convert <div> to <br>
     *
     * @param  string $html
     *
     * @return string
     */
    public function div2br($html)
    {
        return preg_replace('~<div>~', "<br>", $html);
    }

    /**
     * generate a slug
     *
     * @param string $title
     * @param mixed  $model
     * @param string $key Optionally you may specify the attribute (default is slug)
     *
     * @return bool|mixed|string
     */
    public function slugify($slug, $model = null, $key = 'slug')
    {
        $slug = str_replace('؟', '', $slug);
        $slug = str_replace('!', '', $slug);
        $slug = str_replace('ّ', '', $slug);
        $slug = str_replace(' ', '-', $slug);
        $slug = preg_replace('/[^\x{0600}-\x{06FF}A-Za-z0-9-_]/u', '', $slug);

        $slug = mb_strtolower($slug, 'UTF-8');

        if ($model) {
            $key = ($key) ? $key : 'slug';
            $count = $model->where($key, '=~', "$slug.*")->count();

            return ($count > 0) ? "{$slug}-{$count}" : $slug;
        }

        return $slug;
    }

    /**
     * generate a hash based on the id sent
     *
     * @param $id
     *
     * @return string
     */
    public function generateHash($id)
    {
        return md5($id . time());
    }

    /**
     * format the date for a better json response
     *
     * @param $date
     *
     * @return string
     */
    public function formatDate($date)
    {
        return (new \DateTime($date))->format('c');
    }

    /**
     * Format the date using carbon
     *
     * @param             $date
     * @param bool        $instance determines if he wants a carbon instance to be returned or the normal string
     * @param null|string $timezone
     *
     * @param bool        $to_utc
     *
     * @return \Carbon\Carbon|string
     */
    public static function formatDateUsingCarbon($date, $instance = false, $timezone = 'UTC', $to_utc = true)
    {
        // create the carbon object if $date not exist in the parameter
        if (!$date) {
            $date = Helper::getCurrentDateTime();
        }

        // build the carbon instance from the parameter input if date is provided
        if (!$date instanceof Carbon) {
            $date = new Carbon($date, (! empty($timezone)) ? $timezone : 'UTC');
        }

        // return carbon instance if needed or date as string, with options to either converted to UTC or not
        return ($instance) ? $date : ($to_utc) ? Helper::convertToUTC($date)->toDateTimeString() : $date->toDateTimeString();
    }

    /**
     * @param bool $as_string
     *
     * @return string|static
     */
    public static function getCurrentDateTime($as_string = false)
    {
        $date = Carbon::now(config('app.timezone'));

        return $as_string ? $date->toDateTimeString() : $date;
    }

    /**
     * convert carbon date to UTC timezone
     *
     * @param \Carbon\Carbon $date
     *
     * @return static
     */
    public static function convertToUTC(Carbon $date)
    {
        return $date->setTimezone('UTC');
    }


    /**
     * Make sure the admin has the right to set the publishing state to what is claimed
     * and no invalid values goes through.
     *
     * @param  string|array $state
     *
     * @return string
     */
    public function filterPublishState($state)
    {
        // We don't wanna go through anything if the state is null.
        if (is_null($state)) {
            return '';
        }

        $publish_state = self::pluckState($state);

        // If the admin is trying to post it as a published article but has no publish
        // rights then we'll downgrade it to editing.

        /**
         * @todo Pass the publish state through an enum validation pipeline.
         */

        if (!Auth::hasPermission('publish') && $publish_state == 'published') {
            $publish_state = self::pluckState('editing');
        }

        return $publish_state;
    }

    /**
     * concatenate the $title with $date to prepare the input to be passed to the slugify
     *
     * @param $title
     * @param $date
     *
     * @return string
     */
    public function concatTitleAndDate($title, $date)
    {
        return $this->concatTwoStrings($title, $date);
    }

    /**
     * @param $slug_former
     * @param $date
     *
     * @return string
     */
    public function concatSlugFormerAndDate($slug_former, $date)
    {
        return $this->concatTwoStrings($slug_former, $date);
    }

    /**
     * @param $slug_former
     *
     * @return string
     */
    public function concatSlugFormerWithRandomNumber($slug_former)
    {
        return $this->concatTwoStrings($slug_former, rand(1111,9999));
    }


    /**
     * @param        $string_1
     * @param        $string_2
     * @param string $concatinator
     *
     * @return string
     */
    private function concatTwoStrings($string_1, $string_2, $concatinator = '-')
    {
        return trim($string_1) . $concatinator . trim($string_2);
    }

    /**
     * return the state.
     *
     * @param  array $state
     *
     * @return String                 editing, published, scheduled.
     */
    public function pluckState($state)
    {
        if (is_null($state)) {
            return null;
        }
        // accept non-array values allowing passing in just a string.
        $state = (array) $state;

        if (in_array('editing', $state)) {
            return 'editing';
        } elseif (in_array('published', $state)) {
            return 'published';
        } elseif (in_array('scheduled', $state)) {
            return 'scheduled';
        } else {
            return '';
        }
    }

    /**
     * sort videos by ID number because all the dates are exactly the same
     *
     * @param $data
     *
     * @return mixed
     */
    public function sortVideos($data)
    {
        usort($data, function($a, $b)
        {
            return strcmp($a->id, $b->id);
        });

        return $data;
    }


    /**
     * convert a slug to it's original state (before sluggification)
     * example:
     *      input:   i-am-the-slug-2015-4-27-1145
     *      output:  i am the slug
     *
     * @param $slug
     *
     * @return mixed
     */
    public static function dissluggify($slug)
    {
        // get the last part of the string after the last `-`
        $part = substr($slug, strrpos($slug, '-') + 1);
        // check it's a number in order to be removed
        if(is_numeric($part)){
            // remove that part from the string and remove the `-` as well
            // so here we'll subtract the the part size + 1 from the original string
            // then we'll call the same function to check for the same procedure
            return self::dissluggify(substr($slug, 0, -(strlen($part) + 1)));
        }
        // when no more numbers in the string replace all the `-` with space
        return str_replace('-', ' ', $slug);
    }


     /**
     * generate a shared url then shorten it
     *
     * @param      $model
     * @param      $slug
     *
     * @param null $domain
     *
     * @return string
     */
    public static function generateShortShareUrl($model, $slug = null, $domain = null)
    {
        $share_url = self::generateShareUrl($model, $slug, $domain);
        return self::shoretnize($share_url);
    }


    /**
     * generate the long share url for posts
     *
     * @param      $model  the content entity to identify the type of the content (empty entity)
     * @param      $slug   the slug
     * @param null $domain set your domain or the default app domain will be used
     *
     * @return string
     */
    public static function generateShareUrl($model, $slug = null, $domain = null)
    {

        $name = strtolower(str_replace('Agency\\', '', get_class($model)));

        

        $composed_domain = (!is_null($domain) ? $domain : Config::get('app.url')) . '/' . $name;

        if (is_null($slug)) {
            $slug = $model->slug;
        }

        return $composed_domain . '/' . $slug;
    }





    /**
     * call the third party package to short the url
     *
     * @param $url
     *
     * @return mixed
     */
    public static function shoretnize($url)
    {
        try {
            $share_url = Shorten::url($url);
        } catch(\Vinelab\UrlShortener\Exceptions\Exception $e) {
            // this error might be thrown when trying to shorten a `localhost` domain
            $share_url = null;
        }

        return $share_url;
    }


}
