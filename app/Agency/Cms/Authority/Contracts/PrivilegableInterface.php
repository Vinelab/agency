<?php namespace Agency\Cms\Authority\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface PrivilegableInterface {
    /**
     * A morph relation to privileges.
     *
     * @return  Illuminate\Database\Eloquent\Relations\MorphOneOrMany
     */
    public function privileges();

    /**
     * Returns the ID of this record.
     *
     * @return mixed
     */
    public function identifier();

    /**
     * Returns the identifier key.
     *
     * @return string
     */
    public function identifierKey();

    /**
     * The alias of the section.
     * With what to reference the section in public.
     *
     * @return string
     */
    public function alias();

}