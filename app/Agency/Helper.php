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
}