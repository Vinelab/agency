# Agency

[![Build Status](https://travis-ci.org/Vinelab/agency.svg?branch=develop)](https://travis-ci.org/Vinelab/agency)

A backend for dedicated products implementing an easy to use content management system and exposes
data through an API specifically tailored for web and mobile.

## Installation

### Composer
- [install composer](https://getcomposer.org/doc/00-intro.md#globally)
- create your agency based project with ```composer create-project vinelab/agency my-project```

### Setup

- let Laravel know what environment you're running under in ```bootstrap/start.php```
by checking this line:

```php
$env = $app->detectEnvironment(array(

    'development' => array('agency-develop') // as set in http://githb.com//vinelab/agency-deployment-provisioning

));
```
- make sure the database is configured as expected in ```app/config/[environment]/database.php```


### Database Migration and Seeding

inside the project directory:

- migrate with ```php artisan migrate```
- seed with ```php artisan db:seed```

## Launch

If you're following the steps in the [deployment repo](https://github.com/Vinelab/agency-deployment-provisioning),
visiting ```http://agency.dev:7878``` should open it up.

- login to the CMS by visiting ```http://agency.dev:7878/cms```
    - user: ```admin@vinelab.com```
    - pass: ```meh```
> remember to change them after logging in

## Features

- Multi admin access control with fine-grained role/permission assignment
- Token based authenticated API to expose content to all sorts of consumers (mobile, web, third-parties, etc.)
- Very cool looking and customizable interface :sunglasses: thanks to [Ace Admin](http://wrapbootstrap.com/preview/WB0B30DGR)
- [Amazon AWS S3](http://aws.amazon.com/s3/) CDN support for uploaded assets
- Image cropping with specified ratio and/or size
- Organize and retrieve content by sections and categories
- Youtube video integration
- Harmless text editor that cleans the :shit: out of any text inserted or pasted in
