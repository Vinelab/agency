<?php namespace Agency\Caching;

class Range {

    protected $start;

    protected $stop;

    public function __construct($limit, $offset)
    {
        $this->start = $this->calculateStart($limit, $offset);
        $this->stop  = $this->calculateStop($limit, $offset);
    }

    public function start()
    {
        return $this->start;
    }

    public function stop()
    {
        return $this->stop;
    }

    /**
     * Calculate the index where the Range starts.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return int
     */
    public function calculateStart($limit, $offset)
    {
        // Range works in a 0-based index so the 'start' value at the start index is included the fetched data.
        $start = intval($offset);

        if ($offset > 0) {
            $start = $offset + 1;
        }

        return $start;
    }

    /**
     * Calculate the index where the Range ends.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return int
     */
    public function calculateStop($limit, $offset)
    {
        if ($offset > 0) {
            $stop = intval($limit) + intval($offset);
        } else {
            $stop = intval($limit) + (intval($offset) - 1);
        }
        // The last record to be fetched by LRANGE will be the sum of both the offset and the limit.
        return $stop;
    }
}
