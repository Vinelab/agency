<?php namespace Agency\RealTime;

use Config;
use Vinelab\Minion\Dictionary;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Pagination {

    /**
     * The limit value.
     *
     * @var int
     */
    protected $limit;

    /**
     * The offset value.
     *
     * @var int
     */
    protected $offset;

    /**
     * Constructor.
     *
     * @param int $limit
     * @param int $offset
     */
    public function __construct($limit, $offset)
    {
        $this->setLimit($limit);
        $this->setOffset($offset);
    }

    /**
     * Get the limit value.
     *
     * @return int
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * Get the offset value.
     *
     * @return int
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * Set the limit value for this pagination instance.
     *
     * @param int $limit
     */
    public function setLimit($limit)
    {
        // Set the configured value to be the default limit value.
        if (! $this->limit) {

            $this->limit = Config::get('api.limit');
        }

        // We should validate the pagination value before we set it.
        if (isset($limit) && ! empty($limit) && $limit <= Config::get('api.limit')) {
            $this->limit = $limit;
        }
    }

    /**
     * Set the offset value for this pagination instance.
     *
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = (isset($offset) && ! empty($offset)) ? $offset : 0;
    }

    /**
     * Get a new Pagination instance.
     *
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return \Fahita\RealTime\Pagination
     */
    public static function make(Dictionary $data)
    {
        return new static($data->limit, $data->offset);
    }
}
