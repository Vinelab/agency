<?php namespace Agency;

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
        return $this->aliasOrSlug($string);
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

        if(strpos($html, '<div>'))
        {
            $text = $this->div2br($text);
            $text = $this->br2nl($text);
        }

        $text = strip_tags($text, '<a><br><b><strike><u><i>');

        $text = $this->br2nl($text);

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
        return preg_replace('~<br />|<br>~', "\n", $html);
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

    public function slugify($title, $model = null)
    {
        
        $slug = $this->aliasOrSlug($title);

        if ($model)
        {
            $count = $model->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->count();
            return ($count > 0) ? "{$slug}-{$count}" : $slug;
        }

        return $slug;
    }

    public function aliasOrSlug($title)
    {
        $seo_st    = str_replace(' ', '-', $title);
        $seo_alm   = str_replace('--', '-', $seo_st);
        $title_seo = str_replace(' ', '', $seo_alm);

        return  mb_strtolower($title_seo, 'UTF-8');

    }

    public function getUniqueId()
    {
        return uniqid();
    }

}
