<?php namespace Agency\Cms\Repositories\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface SectionRepositoryInterface {

    public function create($title, $alias = '', $icon, $parent_id, $is_fertile = false, $is_roleable = false);

    public function update($id, $title, $alias, $icon, $parent_id, $is_fertile, $is_roleable);

    public function roleable();

    public function initial($additional = []);

    public function infertile();

    public function set($section);
}