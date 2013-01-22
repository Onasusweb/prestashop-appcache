<?php

/**
 *	Prestashop Appcache
 *	https://github.com/romainberger/prestashop-appcache
 *
 *	@author Romain Berger <romain@romainberger.com>
 *	@version 0.1
 */

class Appcache {

	public function __construct() {

	}

	/**
	 *	Parse a directory to get all the filenames
	 *
	 *	@param string $path Path to the directory
	 *	@param string $extension Extension of the files to return
	 *	@return array
	 */
	public function parseDirectory($path, $extension) {
		$files = scandir($path);

		// filter the files to only return the type wanted

		return $files;
	}

	/**
	 *	Make sure the appcache attribute is in the html tag
	 *	At least that's the goal but parsing html is going
	 *	to be a pain in the ass so we'll see
	 */
	public function addAttribute() {

	}

	/**
	 *	Write the file
	 */
	public function write() {

	}

}
