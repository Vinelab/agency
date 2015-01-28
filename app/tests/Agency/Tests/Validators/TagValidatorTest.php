<?php namespace Agency\Tests\Validators;

use Agency\Tag;
use DB, Artisan, TestCase;
use Agency\Validators\TagValidator;

class TagValidatorTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->mValidatorFactory = $this->app->make('Illuminate\Validation\Factory');
        $this->validator = new TagValidator($this->mValidatorFactory);
    }

    public function test_tag_validator_binding()
    {
        $validator = $this->app->make('Agency\Contracts\Validators\TagValidatorInterface');
        $this->assertInstanceOf('Agency\Validators\TagValidator', $validator);
    }

    public function test_passing_tag_validation()
    {
        $this->validator->validate(['text' => 'some text here']);
    }

    /**
     * @depends test_passing_tag_validation
     * @expectedException Agency\Exceptions\InvalidTagException
     */
    public function test_fails_with_missing_text()
    {
        $this->validator->validate(['something' => 'else']);
    }

    /**
     * @depends test_passing_tag_validation
     * @expectedException Agency\Exceptions\InvalidTagException
     */
    public function test_fails_with_null_text()
    {
        $this->validator->validate(['text' => null]);
    }

    /**
     * @depends test_passing_tag_validation
     * @expectedException Agency\Exceptions\InvalidTagException
     */
    public function test_fails_with_empty_text()
    {
        $this->validator->validate(['text' => '']);
    }

    /**
     * @depends test_passing_tag_validation
     * @expectedException Agency\Exceptions\InvalidTagException
     */
    public function test_fails_with_long_text()
    {
        $this->validator->validate(['text' => 'basdgfkajsdhgfkjahsdgfkjhasbdf,asdgfkuagsdfj,hbasdfb,asdfmnbasdjhfg adhfg kajshd fgk asdfgkadsjfg aksjdhfg akjdsgf aksdjhfg kajsdfg kajdsg fkajdsg fkjasdg fkjasdg fkjahsdg fkjadshg fkahjsgd fkjhsdag fkjashdg fkjadgs fkjasdg faks jdgfkjadgs fkajg asdfgakjsd']);
    }

    /**
     * @depends test_passing_tag_validation
     * @expectedException Agency\Exceptions\InvalidTagException
     */
    public function test_fails_with_existing_text()
    {
        DB::table(['Tag'])->insert([
            'text' => 'find-me',
            'created_at' => date(time()),
            'updated_at' => date(time())
        ]);

        $this->validator->validate(['text' => 'find-me']);
    }
}
