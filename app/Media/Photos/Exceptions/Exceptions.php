<?php namespace Agency\Media\Photos\Exceptions;

use RuntimeException;

class InvalidMediaTypeException extends RuntimeException {}

class UploadedPhotoException extends RuntimeException {}

class UploadedPhotoMetaException extends UploadedPhotoException {}

class PhotoUploadException extends UploadedPhotoException {}

class InvalidPhotoInstanceException extends UploadedPhotoException {}

class UnrecognizedFileTypeException extends UploadedPhotoException {}
