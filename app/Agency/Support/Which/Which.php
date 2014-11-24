<?php namespace Agency\Support\Which;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

class Which {

    /**
     * @var \Agency\Support\Which\Sections
     */
    private $sections;

    public function __construct(Sections $sections)
    {
        $this->sections = $sections;
    }

    /**
     * Get the current section being visited.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function section()
    {
        return $this->sections->current();
    }

    public function category()
    {
        return $this->sections->currentCategory();
    }

}
