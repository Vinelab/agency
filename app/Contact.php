<?php namespace Agency;

use NeoEloquent;

/**
 * Class Contact
 *
 * @category Entity
 * @package  AblaFahita
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class Contact extends NeoEloquent
{

    public $timestamps = true;

    protected $label = ['Contact'];

    protected $fillable = ['name', 'phone', 'email', 'message', 'country', 'type'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

}
