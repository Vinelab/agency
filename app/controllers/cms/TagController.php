<?php namespace Agency\Cms\Controllers;

use Input, Response, Redirect, Lang;

use Agency\Cms\Repositories\Contracts\RoleRepositoryInterface as Roles;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;
use Agency\Cms\Validators\Contracts\RoleValidatorInterface as RoleValidator;

use Agency\Cms\Exceptions\UnauthorizedException;

class TagController extends Controller {

	public function __construct(SectionRepositoryInterface $sections,
    							TagRepositoryInterface $tag)
    {
        parent::__construct($sections);

		$this->section          = $sections;
		$this->tag 				= $tag;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return dd($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	public function all()
	{
		return Response::json(['tags'=>$this->tag->all()->fetch('text')->toJson()]);
	}

}