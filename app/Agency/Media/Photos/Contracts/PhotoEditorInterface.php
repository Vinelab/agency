<?php namespace Agency\Media\Photos\Contracts;

interface PhotoEditorInterface {

    public function crop($file, $width, $height, $x = 0, $y = 0);

    public function resize($file, $width, $height);

    public function scale($file, $width, $height);

    public function cache($photo);

    public function orientation($width, $height);

    public function makePhoto($path);
}