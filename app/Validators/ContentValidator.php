<?php namespace Agency\Validators;

use Agency\RealTime\Content;
use Agency\Validators\Validator;
use Agency\Exceptions\InvalidContentException;
use Agency\Contracts\Validators\ContentValidatorInterface;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class ContentValidator extends Validator implements ContentValidatorInterface {

    protected $rules = [
        'id'   => 'required|max:255',
        'type' => 'required|in:'.Content::TYPE_ARTICLE.','.Content::TYPE_PHOTO_ALBUM.','.Content::TYPE_VIDEO.','.Content::TYPE_ANY,
    ];

    public function validate($attributes)
    {
        $validation = $this->validation($attributes);

        if ($validation->fails()) {
            throw new InvalidContentException($validation->messages()->first());
        }

        return true;
    }
}
