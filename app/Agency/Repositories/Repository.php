<?php namespace Agency\Repositories;

/**
 * @author Adib Hanna <adib@vinelab.com>
 */

abstract class Repository implements Contracts\RepositoryInterface {

	/**
	 * The model.
	 * 
	 * @var mixed
	 */
	protected $model;

	/**
	 * Find a single record of the model.
	 * 
	 * @param  mixed $id 
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find a single record by an attribute.
     * 
     * @param  string $attribute 
     * @param  string $value     
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findBy($attribute, $value)
    {
        return $this->model->where($attribute, $value)->first();
    }

    /**
     * Fill out an instance of the model and return it.
     * 
     * @param  array $attributes 
     * @return Illuminate\Database\Eloquent\Model        
     */
    public function fill($attributes)
    {
            return $this->model->fill($attributes);
    }

    /**
     * Fills out an instance of the model and saves it.
     * 
     * @param  array $attributes 
     * @return Illuminate\Database\Eloquent\Model             
     */
    public function fillAndSave($attributes)
    {
            $this->model->fill($attributes);
            $this->model->save();

            return $this->model;
    }

    /**
     * Implementing this magic method
     * to provide the convenience of calling
     * findBy{Attribute} instead of findBy({Attribute})
     * i.e. findByName or findByAlias are equivalent to
     * 		findBy('name') and findBy('alias')
     * 
     * @param  string $method
     * @param  array $arguments 
     * @return mixed            
     */
    public function __call($method, $arguments)
    {
            /**
             * findBy convenience calling to be available
             * through findByName and findByTitle etc.
             */

            if (preg_match('/^findBy/', $method))
            {
                    $attribute = strtolower(substr($method, 6));
                    array_unshift($arguments, $attribute);
                    return call_user_func_array(array($this, 'findBy'), $arguments);
            }
    }
}