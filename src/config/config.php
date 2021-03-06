<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Debug Mode
	|--------------------------------------------------------------------------
	|
	| It does not perform the delete and write cache If you enable this option.
	|
	| 
	*/

	'debug' => false,

	/*
	|--------------------------------------------------------------------------
	| Nginx Cache Location
	|--------------------------------------------------------------------------
	|
	| Please specify the directory where the cache of the proxy or FastCGI is 
	| stored.
	|
	*/

	'nginx_cache_path' => '/var/run/nginx-cache',


	/*
	|--------------------------------------------------------------------------
	| Nginx Cache Levels
	|--------------------------------------------------------------------------
	|
	*/

	'nginx_levels' => '1:2',


	/*
	|--------------------------------------------------------------------------
	| X-Accel-Expires
	|--------------------------------------------------------------------------
	|
	*/

	'expires'	=> '86400',

);