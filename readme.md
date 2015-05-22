# Agency

[![Build Status](https://travis-ci.org/Vinelab/agency.svg?branch=develop)](https://travis-ci.org/Vinelab/agency)

A backend for dedicated products implementing an easy to use content management system and exposes
data through an API specifically tailored for web and mobile.

## Agency based on on Laravel 5.0 and Ace - Responsive Admin Template
* [Features](#feature1)
* [Requirements](#feature2)
* [How to install](#feature3)
* [License](#feature4)
* [How Agency site is look like](#feature5)

<a name="feature1"></a>
## Starter Site Features:
* Laravel 5.0
* Twitter Bootstrap 3.2.0
* Based on neo4j database
* Back-end
	* Multi admin access control with fine-grained role/permission assignment.
    * Very cool looking and customizable interface :sunglasses: thanks to [Ace Admin](http://wrapbootstrap.com/preview/WB0B30DGR)
	* Support for different languages Currently (Arabic and English).
	* Crope photos easily using mr-uploader.
	* Uploads photos to Amazone S3.
    * Easy to use Textbox editor
    * Store embedded content from (Facebook - Youtube - Twitter - Instagram).
	* Load public assets from CDN.
    * Support for multiple sections and sub section
    * Token based authenticated API to expose content to all sorts of consumers (mobile, web, third-parties, etc.)
    * Cached API Response
    * Short URL will be generated automatically for each post using Bitly
	* User login, registration
	* Email notification


-----
<a name="feature2"></a>
##Requirements

	PHP >= 5.5.0
	MCrypt PHP Extension
    Redis Server
    Neo4j Database server
-----
<a name="feature3"></a>
##How to install:
* [Step 1: Get the code](#step1)
* [Step 2: Use Composer to install dependencies](#step2)
* [Step 3: Set .env variables](#step3)
* [Step 4: Publish packages config files](#step4)
* [Step 5: Seed the database](#step5)
* [Step 6: Login Page](#step6)
* [Step 7: Create Sections](#step7)
* [Step 8: Create Posts](#step8)
* [Step 9: Retrieve Posts through the API](#step9)



-----
<a name="step1"></a>
### Step 1: Get the code - Download the repository

    https://github.com/Vinelab/agency.git

Extract it in www(or htdocs if you using XAMPP) folder and put it for example in agency folder.

-----
<a name="step2"></a>
### Step 2: Use Composer to install dependencies

Laravel utilizes [Composer](http://getcomposer.org/) to manage its dependencies. First, download a copy of the composer.phar.
Once you have the PHAR archive, you can either keep it in your local project directory or move to
usr/local/bin to use it globally on your system.
On Windows, you can use the Composer [Windows installer](https://getcomposer.org/Composer-Setup.exe).

Then run:

    composer install
to install dependencies Laravel and other packages.

-----
<a name="step3"></a>
### Step 3: Set .env variables

In Order to Agency work properly you have to set some environment variables
* AWS_ACCESS_KEY
* AWS_ACCESS_SECRET
* AWS_S3_BUCKET

-----
<a name="step4"></a>
### Step 4: Publish packages config files

```php artisan vendor:publish```

-----
<a name="step5"></a>
### Step 5: Seed the database

    php artisan db:seed

-----
<a name="step6"></a>
### Step 6: Login Page

Add the following domains to your hosts file
* cms.[your-domain]
* api.[your-domain]

Type in your web browser

	http://cms.[your-domain]

You can now login to admin part of Laravel Framework 5  Bootstrap 3 Starter Site:

    username: admin@admin.com
    password: admin

-----
<a name="step7"></a>
### Step 7: Login Page

After the system is up and running. It's time to create sections:
* Go to the configuration section
* Create new section
    * Add the Id of the Parent Section (Id of the Content Section)
    * if this is a Parent Section for many other subsections Check Fertile

-----

<a name="step8"></a>
### Step 8: Create Post

Go to the Content Section and click on Create Post.
* Add Post Title
* Post body can hold embedded contents from Facebook, Twitter, Youtube, Instagram
* Assign Post to a specific section
* Add many tags to each posts. 
* Add Post Cover Image
* Select post publish status
* Create Post

-----

<a name="step9"></a>
### Step 9: Retrieve Posts through the API

In Order to retrive the posts through the api go to:

* All posts of a specific section
```http://api.[your-domain]/posts?category=[section-alias]&code=code```

* Retrieve a specific post by id or by post slug
```http://api.[your-domain]/posts/{idOrSlug}?code=code```



-----


<a name="feature4"></a>
## License

This is free software distributed under the terms of the MIT license

-----

<a name="feature5"></a>
##How Agency Site is look like

![Login](http://i57.tinypic.com/2yoopr7.png)
![Admin dashboard](http://i61.tinypic.com/1sawiq.png)
![Configuration Section](http://i62.tinypic.com/152j5f9.png)
![Create New Post](http://i60.tinypic.com/2a7fu5f.png)