<?php namespace Agency\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface HelperInterface {
    /**
     * Convert <br> to \n
     *
     * @param  string $html
     *
     * @return string
     */
    public function br2nl($html);

    /**
     * Transform a normal HTML into
     * a stripped HTML (no tags attributes
     * except the href in the a tags)
     *
     * @param  string $html
     *
     * @return string
     */
    public function cleanHTML($html);

    /**
     * Convert <div> to <br>
     *
     * @param  string $html
     *
     * @return string
     */
    public function div2br($html);

    /**
     * Transforms a camelCase string to
     * snake-case.
     *
     * @param  string $string
     *
     * @return string
     */
    public function aliasify($string);

    /**
     * generate a slug
     *
     * @param      $title
     * @param null $model
     *
     * @return bool|mixed|string
     */
    public function slugify($title, $model = null);

    /**
     * generate a hash based on the id sent
     *
     * @param $id
     *
     * @return string
     */
    public function generateHash($id);


    /**
     * format the date for a better json response
     *
     * @param $date
     *
     * @return string
     */
    public function formatDate($date);

    /**
     * return the state.
     *
     * @param  array $state
     * @return String                 editing, published, scheduled.
     */
    public function pluckState($state);

    /**
     * Make sure the admin has the right to set the publishing state to what is claimed
     * and no invalid values goes through.
     *
     * @param  string|array $state
     * @return string
     */
    public function filterPublishState($state);

    /**
     * @param      $date
     * @param bool $instance
     *
     * @return mixed
     */
    public static function formatDateUsingCarbon($date, $instance = false, $timezone = 'UTC', $to_utc = true);

    /**
     * concatenate the $title with $date to prepare the input to be passed to the slugify
     *
     * @param $title
     * @param $date
     */
    public function concatTitleAndDate($title, $date);

    /**
     * @param $slug_former
     * @param $date
     *
     * @return mixed
     */
    public function concatSlugFormerAndDate($slug_former, $date);

    /**
     * @param $slug_former
     *
     * @return mixed
     */
    public function concatSlugFormerWithRandomNumber($slug_former);
}
