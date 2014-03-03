<?php namespace Agency;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

class Helper {

    /**
     * Transforms a camelCase string to
     * snake-case.
     *
     * @param  string $string
     * @return string
     */
    public static function aliasify($string)
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
    public static function cleanHTML($html)
    {

        $text = preg_replace('~</(p|div|h[0-9])>~', '</$1><br />', $html);

        if(strpos($html, '<div>'))
        {
            $text = Helper::div2br($text);
            $text = Helper::br2nl($text);
        } 

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
    public static function br2nl($html)
    {
        return preg_replace('~<br />|<br>~', "\n", $html);
    }

    /**
     * Convert <div> to <br>
     * 
     * @param  string $html 
     * @return string       
     */
    public static function div2br($html)
    {
        return preg_replace('~<div>~', "<br>", $html);
    }
}