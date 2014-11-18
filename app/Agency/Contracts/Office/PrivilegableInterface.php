<?php namespace Agency\Contracts\Office;

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
    public function getKey();

    /**
     * Returns the identifier key name.
     *
     * @return string
     */
    public function getKeyName();

    /**
     * The alias of the section.
     * With what to reference the section in public.
     *
     * @return string
     */
    public function alias();

}
