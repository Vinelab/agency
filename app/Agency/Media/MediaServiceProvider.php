<?php namespace Agency\Media;

use Illuminate\Support\ServiceProvider;

use App;
use Agency\Media\Photos\Manager as PhotosManager;
use Agency\Media\Photos\AwsPhotoUploader;

class MediaServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(
            'Agency\Media\Photos\Contracts\PhotoEditorInterface',
            'Agency\Media\Photos\InterventionPhotoEditor');

        $this->app->bind(
            'Agency\Media\Photos\Contracts\StoreInterface',
            'Agency\Media\Photos\Store');

        $this->app->bind(
            'Agency\Media\Videos\Contracts\ParserInterface',
            'Agency\Media\Videos\Parser');
        
        $this->app->bind(
            'Agency\Media\Photos\Contracts\PhotoUploaderInterface', function(){
                return new AwsPhotoUploader(App::make('config'));
            });

        $this->app->bind(
            'Agency\Media\Photos\Contracts\ManagerInterface', function(){
                return new PhotosManager(App::make('config'),
                                        App::make('Agency\Media\Photos\Contracts\UploadInterface'),
                                        App::make('Agency\Media\Photos\Photo'),
                                        App::make('Agency\Media\Photos\Contracts\PhotoEditorInterface'),
                                        App::make('Agency\Media\Photos\Contracts\PhotoUploaderInterface'));
            });

        $this->app->bind(
            'Agency\Media\Photos\Contracts\UploadInterface','Agency\Media\Photos\AwsUpload');

        $this->app->bind(
            'Agency\Media\Photos\Contracts\FilterResponseInterface','Agency\Media\Photos\FilterResponse');
    }
}