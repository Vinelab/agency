<?php namespace Agency\Http;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use App;
use Route;
use Config;
use Illuminate\Http\Request as IlluminateRequest;

class Request extends IlluminateRequest {

    /**
     * The site being visited.
     *
     * @var string
     */
    private $site;

    /**
     * Determines whether we're in a request for artists site.
     *
     * @var boolean
     */
    private $for_artists = false;

    /**
     * Supported domains.
     *
     * @var array
     */
    private $domains = [
        'agency'   => ['api', 'cms'],
    ];

    /**
     * The collection of routes that do not need
     * authorization to be accessed.
     *
     * @var array
     */
    protected $open_routes = [
        'cms.login',
        'cms.logout'
    ];


    /**
     * Get the current site sub-domain.
     *
     * @return [type] [description]
     */
    public function site()
    {
        // When the site has already been determined for this request
        // we'll just return it.
        if ( ! is_null($this->site)) return $this->site;

        // When in the testing environment we will consider the configured app url
        // the real one to allow overriding it by simply modifying that value in the config.
        $url = parse_url($this->url());

        $host = $url['host'];

        // find matching supported domains
        $matches = array_filter($this->getSubDomains(), function($domain) use($host)
        {
            preg_match("/{$domain}.*/", $host, $matches);
            if($matches) return $domain;
        });

        $site = reset($matches);

        $this->setSite($site);

        return $site;
    }

    /**
     * Set the current site being visited.
     *
     * @param string $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * Determine whether this request is for artists or not.
     *
     * @return boolean
     */
    public function isForArtists()
    {
        $this->setForArtists($this->site());

        return $this->for_artists;
    }

   /**
     * Determine whether the current route is within the open routes,
     * open routes do not need authorization to be accessed.
     *
     * @return boolean
     */
    public function isOpen()
    {
        return in_array(Route::current()->getAction()['as'], $this->open_routes);
    }

    /**
     * Set whether this request is for artists or not.
     *
     * @param string $site The sub-domain of the visited site
     */
    public function setForArtists($site)
    {
        if (in_array($site, $this->getArtistSubDomains())) $this->for_artists = true;
    }

    /**
     * Get the supported sub domains.
     *
     * @return array
     */
    public function getSubDomains()
    {
        $domains = [];

        foreach ($this->domains as $sub_domains)
        {
            $domains = array_merge($domains, $sub_domains);
        }

        return $domains;
    }

    /**
     * Get the artist-only sub-domains.
     *
     * @return array
     */
    public function getArtistSubDomains()
    {
        return $this->domains['artists'];
    }

    /**
     * Get the agency-only sub-domains.
     *
     * @return array
     */
    public function getAgencySubDomains()
    {
        return $this->domains['agency'];
    }

    /**
     * Detect whether this request is in the testing environment.
     *
     * @return boolean
     */
    protected function isTesting()
    {
        return App::environment() === 'testing';
    }

    /**
     * Return the URL based on the URL in the config.
     *
     * @return string
     */
    public function getTestingUrl()
    {
        return Config::get('app.url') .'/'. $this->path();
    }

    public function url()
    {
        if ($this->isTesting()) return $this->getTestingUrl();

        return parent::url();
    }

}
