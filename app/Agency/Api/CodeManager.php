<?php namespace Agency\Api;

use Agency\Contracts\Api\CodeManagerInterface;

use Illuminate\Redis\Database as RedisManagerInterface;

class CodeManager implements CodeManagerInterface {

	public function __construct(RedisManagerInterface $redis)
	{
		$this->redis = $redis;
		$this->redis = $this->redis->connection('central');
		$this->prefix = "code";
	}

	public function store($key, $value, $duration = null)
	{
		$key = $this->getkey($key);
		if(! is_null($duration))
		{
			return $this->redis->setEX($key, $duration, $value);
		}
		return $this->redis->set($key, $value);
	}

	public function get($key)
	{
		$key = $this->getkey($key);
		return $this->redis->get($key);
	}

	public function remove($key)
	{
		$key = $this->getkey($key);
		return $this->redis->del($key);
	}

	public function getkey($key)
	{
		return $this->prefix.'-'.$key;
	}

}
