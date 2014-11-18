<?php namespace Agency\Support;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Contracts\HelperInterface;

class Helper implements HelperInterface {

    /**
     * Transforms a camelCase string to
     * snake-case.
     *
     * @param  string $string
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
     * Transform a normal HTML into
     * a stripped HTML (no tags attributes
     * except the href in the a tags)
     *
     * @param  string $html
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
        $text = preg_replace('/class=".*?"/','', $text);
        $text = preg_replace('/style=".*?"/','', $text);

        return $text;
    }

    /**
     * Convert <br> to \n
     *
     * @param  string $html
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
     * @param mixed $model
     * @param string $key Optionally you may specify the attribute (default is slug)
     *
     * @return bool|mixed|string
     */
    public function slugify($title, $model = null, $key = 'slug')
    {
        $slug = str_replace(' ', '-', $title);
        $slug = str_replace('--', '-', $slug);
        $slug = str_replace(' ', '', $slug);

        $slug = mb_strtolower($slug, 'UTF-8');

        if ($model)
        {
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
        return md5($id.time());
    }

    /**
     * format the date for a better json response
     *
     * @param $date
     *
     * @return string
     */
    public function formatDate($date){
        return (new \DateTime($date))->format('c');
    }
}
