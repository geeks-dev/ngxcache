# Nginx Cache Controller for Laravel 4

[日本語ドキュメントはコチラ](http://www.geeks-dev.com/laravel%E3%81%A7nginx%E3%81%AE%E3%82%AD%E3%83%A3%E3%83%83%E3%82%B7%E3%83%A5%E3%82%92%E5%88%B6%E5%BE%A1%E3%81%99%E3%82%8Bngxcache/)

## Installation

Add `geeks-dev/ngxccache` as a requirement to composer.json.

	{
		"require": {
			"geeks-dev/ngxccache": "dev-master"
		}
	}

Update your packages with composer update or install with composer install.

Once Ngxcache is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

	'providers' => array(

		'Geeksdev\Ngxcache\NgxcacheServiceProvider'

	)

Ngxcache also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

	'aliases' => array(

		'Ngxcache' => 'Geeksdev\Ngxcache\Facades\Ngxcache'

	)

### Configure Nginx server

Add `fastcgi_pass_header` (`X-Accel-Redirect`,`X-Accel-Buffering`,`X-Accel-Charset`,`X-Accel-Expires`,`X-Accel-Limit-Rate`) .

	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_index index.php;

		fastcgi_pass_header "X-Accel-Redirect";
		fastcgi_pass_header "X-Accel-Buffering";
		fastcgi_pass_header "X-Accel-Charset";
		fastcgi_pass_header "X-Accel-Expires";
		fastcgi_pass_header "X-Accel-Limit-Rate";

		fastcgi_cache_key       $scheme://$host$request_uri;
							.
							.
							.

### Congirure Package

Run this command. 

	php artisan config:publish geeks-dev/ngxcache

<br />
See the configuration file of your Nginx.

	fastcgi_cache_path /var/run/nginx-cache levels=1:2 keys_zone=cache:4m inactive=7d max_size=50m;

<br />
Edit `app/config/packages/geeks-dev/ngxcache/config.php`
Please specify the directory where the cache of the proxy or FastCGI is stored
	
	/*
	|--------------------------------------------------------------------------
	| Nginx Cache Location
	|--------------------------------------------------------------------------

	'nginx_cache_path' => '/var/run/nginx-cache',

	/*
	|--------------------------------------------------------------------------
	| Nginx Cache Levels
	|--------------------------------------------------------------------------
	*/

	'nginx_levels' => '1:2',

## Ngixcache Commands

	ngxcache:purge <URI>        Nginx purge single cache. (URL argument is required.)
	ngxcache:purge-all          Nginx purge cache of all.
	ngxcache:rebuild <URI>      Nginx cache rebuild. (URL argument is required.)
	ngxcache:refresh-all        Nginx refresh and build cache of all.
	ngxcache:search <URI>       Nginx search single cache. (URL argument is required.)
	ngxcache:show               Nginx show cache of all.


## Make Ngixcache Filters

Usually are not cached .  You can select the page to be cached by that, as shown in the following example , you define a filter .

`filter.php`**Example**

	Route::filter('nginx.cache-enable', function()
	{
		Ngxcache::enable();
	});

	Route::filter('nginx.cache-disable', function()
	{
		Ngxcache::disable();
	});

<br >
`routes.php`**Example**

	Route::group(array('prefix' => '', 'before' => 'nginx.cache-enable'), function(){
		Route::get('/', 'HomeController@getIndex');
		Route::get('{id}/show', 'HomeController@getShow');
	});

	Route::group(array('prefix' => '', 'before' => 'nginx.cache-disable'),function(){

		Route::post('auth/login', 'AuthController@postIndex');
		Route::controller('auth', 'AuthController');
	});


## Ngixcache Methods

	/**
	 * Search nginx cache of all.
	 * example:[$result = Ngxcache::items();]
	 *
	 * @return result
	 */
	Nginx::items();

	/**
	 * Purge nginx cache of all.
	 * example:[Ngxcache::purgeall();]
	 *
	 * @return result
	 */
	Nginx::purgeall();
	
	/**
	 * Purge or search Nginx cache.
	 * example:[Ngxcache::purge($uri);]
	 *
	 * @param  string  $uri
	 * @param  bool    $searchmode
	 * @return result
	 */
	Nginx::purge($uri,true);
	(It does not purge is performed only search Search Mode)

	/**
	 * Rebuild Nginx cache.
	 * example:[Ngxcache::rebuild($uri,true);]
	 *
	 * @param  string  $uri
	 * @param  bool    $overwrite
	 * @return result
	 */
	Nginx::rebuild($uri,true);
	(Second argument will do the forcibly overwritten.
	 　　Only if the cache does not exist , the cache is created normally.)

	/**
	 * Backtrace uri from Nginx cache.
	 * example:[Ngxcache::rebuild($cachePath);]
	 *
	 * @param  string  $cachePath
	 * @return string  $uri
	 */
	Nginx::backtrace($cachePath);


## Trouble shooting

Add the cache directory of Nginx　to `open_basedir` in the `php.ini` If the operation of the cache does not work if.

	(Example)
	open_basedir = .:/usr/share/php:/usr/share/pear:/var/run/nginx-cache

