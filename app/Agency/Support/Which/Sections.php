<?php namespace Agency\Support\Which;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use URL, Request, Input;
use Agency\Contracts\Office\Repositories\SectionRepositoryInterface as SectionRepo;
use Agency\Contracts\Artists\Repositories\SectionRepositoryInterface as ArtistsSectionRepo;


class Sections {

    /**
     * @var \Agency\Contracts\Office\Repositories\SectionRepositoryInterface
     */
    private $sections;

    /**
     * @var \Agency\Support\Which\Artists
     */
    private $artists;

    /**
     * @var \Agency\Contracts\Artists\Repositories\SectionRepositoryInterface
     */
    private $artists_sections;

    /**
     * Used to cache the current section being visited.
     *
     * @var \Illuminate\Database\Eloquent\Model | null
     */
    private $current_section;

    public function __construct(
        Artists $artists,
        SectionRepo $sections,
        ArtistsSectionRepo $artists_sections
    ) {
        $this->sections = $sections;
        $this->artists_sections = $artists_sections;
    }

    /**
     * Get the current section being visited.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function current()
    {
        // First we check whether we've fetched this before and return it if found, otherwise fetch and set.
        $existing = $this->getCurrentSection();

        if ( ! is_null($existing)) return $existing;

        // When we're dealing with a request for artists we have to fetch the corresponding section
        // specific for artists.
        if (Request::isForArtists())
        {
            $section = $this->artists_sections->findByAlias($this->getCurrentSectionAlias());
        }
        else
        {
            $section = $this->sections->findByAlias($this->getCurrentSectionAlias());
        }

        // Cache the current section so that whenever someone asks for it we return
        // it right away without requesting it again.
        $this->setCurrentSection($section);

        return $section;
    }

    public function currentCategory()
    {
        // We don't want to go through anything if there's no category
        if ( ! Input::has('category') || ! Input::get('category')) return null;

        $title = Input::get('category');

        // First we check whether this category has already been fetched and cached
        // so that we return it directly.
        $existing = $this->getCurrentCategory($title);

        if ( ! is_null($existing)) return $existing;

        if (Request::isForArtists())
        {
            $artist = $this->artists->current();

            $category = $this->sections->findForArtist($artist->getKey(), 'alias', $category);
        }

        return $this->getCurrentCategory($title);
   }

    /**
     * Returns the current section's alias
     * parsed out of the URI (URL path).
     *
     * @return string
     */
    protected function getCurrentSectionAlias()
    {
        $url = parse_url(URL::current());

        $path = explode('/', $url['path']);

        if (isset($path[1]) && ! empty($path[1]))
        {
            return $path[1];
        }

        if (isset($path[0]) && ! empty($path[0]))
        {
            return $path[0];
        }

        return $path[0];
    }

    /**
     * Set the current section property.
     *
     * @return void
     */
    protected function setCurrentSection($section)
    {
        $this->current_section = $section;
    }

    /**
     * Getter for the @property $current_section.
     *
     * @return \Illuminate\Database\Eloquent\Model | null
     */
    public function getCurrentSection()
    {
        return $this->current_section;
    }
}
