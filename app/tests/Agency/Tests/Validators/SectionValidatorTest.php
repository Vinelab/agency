<?php namespace Agency\Tests\Validators;

use TestCase;

class SectionValidatorTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->app->make('Agency\Validators\SectionValidator');

        $this->attributes = [
            'title'      => 'my title',
            'alias'      => 'some-alias',
            'icon'       => 'gummy-bear',
            'parent_id'  => 1,
            'is_fertile' => 0,
            'is_roleable' => 0
        ];
    }

    public function test_passing_validation()
    {
        $this->assertTrue($this->validator->validate($this->attributes));
    }

     /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_missing_title()
    {
        $attr = $this->attributes;
        unset($attr['title']);

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_empty_title()
    {
        $attr = $this->attributes;
        $attr['title'] = '';

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_short_title()
    {
        $attr = $this->attributes;
        $attr['title'] = '12';

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_long_title()
    {
        $attr = $this->attributes;
        $attr['title'] = str_repeat('|', 256);

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     */
    public function test_passes_validating_missing_alias()
    {
        $attr = $this->attributes;
        unset($attr['alias']);

        $this->assertTrue($this->validator->validate($attr));
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_alias_with_uppercase()
    {
        $attr = $this->attributes;
        $attr['alias'] = 'Big-One';

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_alias_with_spaces()
    {
        $attr = $this->attributes;
        $attr['alias'] = 'big one';

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_long_aliases()
    {
        $attr = $this->attributes;
        $attr['alias'] = str_repeat('|', 258);

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_missing_icon()
    {
        $attr = $this->attributes;
        unset($attr['icon']);

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_long_icons()
    {
        $attr = $this->attributes;
        $attr['icon'] = str_repeat('icon', 6);

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_missing_parent_id()
    {
        $attr = $this->attributes;
        unset($attr['parent_id']);

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_string_parent_id()
    {
        $attr = $this->attributes;
        $attr['parent_id'] = 'a1';

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     */
    public function test_passes_validating_parent_id_string_evaluating_to_int()
    {
        $attr = $this->attributes;
        $attr['parent_id'] = '10';

        $this->assertTrue($this->validator->validate($attr));
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_missing_fertility()
    {
        $attr = $this->attributes;
        unset($attr['is_fertile']);

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     * @expectedException Agency\Exceptions\InvalidSectionException
     */
    public function test_fails_validating_string_fertility()
    {
        $attr = $this->attributes;
        $attr['is_fertile'] = 'abou l hole';

        $this->validator->validate($attr);
    }

    /**
     * @depends test_passing_validation
     */
    public function test_passes_validating_is_fertile_string_evaluating_to_int()
    {
        $attr = $this->attributes;
        $attr['is_fertile'] = '11';

        $this->assertTrue($this->validator->validate($attr));
    }
}
