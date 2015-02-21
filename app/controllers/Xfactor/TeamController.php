<?php namespace Agency\Cms\Controllers;

/**
 * @author Ibrahim Fleifel <ibrahim@vinelab.com>
 */

use View, Input, Auth;
use Agency\Cms\Controllers\Controller;
use Xfactor\Contracts\Validators\TeamValidatorInterface;
use Xfactor\Contracts\Repositories\TeamRepositoryInterface;
use Agency\Media\Photos\UploadedPhotosCollection;
use Agency\Media\Photos\UploadedPhoto;
use Xfactor\Cache\TeamCacheManager;
use Response;
use Xfactor\Contracts\Services\PhotosServiceInterface;
use Agency\Contracts\Repositories\ImageRepositoryInterface;
use Xfactor\Team;
use Redirect;
use Lang;
use Agency\Contracts\HelperInterface;
use Xfactor\Contracts\Services\ScoreServiceInterface;

use Agency\Helper;

class TeamController extends Controller {

    /**
     * @var Xfactor\Contracts\Repositories\TeamRepositoryInterface
     */
    protected $teams;

    public function __construct(TeamRepositoryInterface $teams,
                                TeamValidatorInterface $validator,
                                TeamCacheManager $team_cache,
                                PhotosServiceInterface $photos_service,
                                ImageRepositoryInterface $image,
                                HelperInterface $helper,
                                ScoreServiceInterface $score_service)
    {
        $this->teams = $teams;
        $this->validator = $validator;
        $this->team_cache = $team_cache;
        $this->photos_service = $photos_service;
        $this->images = $image;
        $this->helper = $helper;
        $this->score_service = $score_service;
    }

    public function index()
    {
        $teams = $this->teams->page();
        
        return View::make('cms.pages.teams.list', ['teams' => $teams]);
    }


    public function create()
    {
        if (Auth::hasPermission('create'))
        {
            return View::make('cms.pages.teams.create');
        }

        throw new UnauthorizedException;
    }


    public function store()
    {

        if (! Auth::hasPermission("create")) {
            return Redirect::back();
        }

        // validate the input
        try {
            $this->validator->validate(Input::all());
        }catch (InvalidTeamException $e){
            return Redirect::route("cms.teams.create", [
                'errors' => $e->messages()
            ])->withInput();
        }

        $relations['image'] = $this->photos_service->parse(Input::get('photo'));

        //use slugify instead of aliasify
        
        $team = $this->teams->createWith(   Input::get('title'),
                                    $this->helper->slugify(Input::get('title'), new Team()),
                                    0,
                                    0,
                                    $relations);
        $this->score_service->createTeamScore($team->slug());

        $this->team_cache->forgetByTags(['teams']);

        return  Redirect::back()->with('success', [Lang::get('teams.messages.created')]);

    }


    public function show($slug)
    {
        if(Auth::hasPermission("read"))
        {
            try {

                $team = $this->teams->findBy("slug",$slug);
                
                return View::make('cms.pages.teams.show',[
                    'team'   => $team
                ]);

            } catch (Exception $e) {
                return Response::json(['message'=>$e->getMessage()]);
            }

        }

        throw new UnauthorizedException;
    }

    public function edit($slug)
    {
        if(Auth::hasPermission("update"))
        {

            try {

                $team = $this->teams->findBy("slug",$slug);

                return View::make("cms.pages.teams.create",["updating_team"=>$team]);

            } catch (Exception $e) {
                return Response::json(['message'=>$e->getMessage()]);
            }

        }

        throw new UnauthorizedException;
    }

    public function update($id)
    {
        if (Auth::hasPermission('update'))
        {
            try {

                $this->validator->validate(Input::get());

                $team = $this->teams->findBy('slug',$id);
                $image = Input::get('photo');

                $relations =[];

                if($team->image->original != $image['original'])
                {
                    $relations['image'] = $this->photos_service->parse(Input::get('photo'));


                }

                ($team->title == Input::get('title')) ? $slug = $team->slug : $slug = $this->helper->slugify(Input::get('title'), new Team());


                $this->teams->update($team->id,
                                     Input::get('title'),
                                     $slug,
                                     $team->score,
                                     $team->user_count,
                                     $relations);

                $this->team_cache->forgetByTags(['teams']);

                return Redirect::route('cms.teams.edit', $id)->with('success', [Lang::get('teams.messages.updated')]);

            } catch (ModelNotFoundException $e) {

                return Redirect::route('cms.teams')->with('errors', [Lang::get('teams.messages.not_found')]);
            } catch (InvalidteamException $e) {
                return Redirect::route('cms.teams.edit', $id)->with('errors', $e->messages());
            }
        }

        throw new UnauthorizedException;
    }

    public function destroy($id)
    {
        if(Auth::hasPermission("delete"))
        {
            try {

                $team = $this->teams->find($id);

                if($this->teams->remove($team->id))
                {
                    $this->team_cache->forgetByTags(['teams']);

                    return Redirect::route("cms.teams");
                }

            } catch (Exception $e) {
                return Response::json(['message'=>$e->getMessage()]);
            }

            $this->team_cache->forgetByTags(['teams']);


        }

        throw new UnauthorizedException;
    }


    public function storePhoto()
    {
        return $this->photos_service->upload();
    }


     /**
     * upload the nominee photo to the CDN
     *
     * @return Agency\Media\Photos\UploadedPhotosCollection
     */
    public function uploadPhotoToCdn()
    {
        try {

            // collect photo data
            $photo_file  = Input::file('photo_file');

            $width  = Input::get('photo_width');

            $height = Input::get('photo_height');

            $crop_x = Input::get('crop_x');
            $crop_y = Input::get('crop_y');
            $crop_width  = Input::get('crop_width');
            $crop_height = Input::get('crop_height');

            // make a photo out of that file
            $photo = UploadedPhoto::make($photo_file, [
                'width'  => $width,
                'height' => $height,
                'crop_x' => $crop_x,
                'crop_y' => $crop_y,
                'crop_width'  => $crop_width,
                'crop_height' => $crop_height
            ]);

            // validate all required parameters
            $photo->validate();
            // the only way is to upload multiple photos
            $collection = new UploadedPhotosCollection;
            $collection->push($photo);

            return $this->uploader->upload($collection, 'Starac/','portrait');

        } catch (FileNotFoundException $e) {

            return Redirect::route('cms.guests')
                ->with('errors', [Lang::get('errors.file_not_found')]);
        }
    }


}
