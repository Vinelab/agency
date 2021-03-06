<?php namespace Agency\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Contracts\Repositories\RepositoryInterface;

class Repository implements RepositoryInterface {

    /**
     * The model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Returns the first record in the database.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * Returns all the records.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find a record by its identifier.
     *
     * @param  string $id
     * @param  array  $relations [optional]
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id, $relations = null)
    {
        if ($relations && is_array($relations))
        {
            $query = $this->model;

            foreach($relations as $relation)
            {
                $query->with($relation);
            }

            return $query->findOrFail($id);
        }

        return $this->model->findOrFail($id);
    }

    /**
     * Find a record by an attribute.
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $relations [optional]
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findBy($attribute, $value, $relations = null)
    {
        if ($relations && is_array($relations))
        {
            $query = $this->model->where($attribute, $value);

            foreach($relations as $relation)
            {
                $query->with($relation);
            }

            return $query->first();
        }

        return $this->model->where($attribute, $value)->first();
    }

    /**
     * Fills out an instance of the model
     * with $attributes
     *
     * @param  array $attributes
     * @return Illuminate\Database\Eloquent\Model
     */
    public function fill($attributes)
    {
        return $this->model->fill($attributes);
    }

    /**
     * Fills out an instance of the model
     * and saves it, pretty much like mass assignment.
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
     * Remove a selected record.
     *
     * @param  string $key
     * @return boolean
     */
    public function remove($key)
    {
        $record = $this->model->where($this->model->getKeyName(), $key)->findOrFail($key);
        return $record->delete();
    }

    /**
     * Implement a convenience call to findBy
     * which allows finding by an attribute name
     * as follows: findByName or findByAlias
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
