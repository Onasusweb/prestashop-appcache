<?php

/**
 *	Prestashop Appcache
 *	https://github.com/romainberger/prestashop-appcache
 *
 *	@author Romain Berger <romain@romainberger.com>
 *	@version 0.1
 */

class Appcache {

	private $files;

	public function generate() {
		// css
		$this->parseDirectory(_PS_THEME_DIR_.'css', 'css');
		// js
		$this->parseDirectory(_PS_THEME_DIR_.'js', 'js');
		// img
		$this->parseDirectory(_PS_THEME_DIR_.'img', 'png');
		return $this->write();
	}

	/**
	 *	Parse a directory to get all the filenames
	 *
	 *	@param string $path Path to the directory
	 *	@param string $extension Extension of the files to return
	 */
	public function parseDirectory($path, $extension) {
		$files = scandir($path);
		$result = array();

		// @TODO parse sub directories
		// @TODO use array for extension (for png / jpg...)
		// filter the files to only return the type wanted
		foreach ($files as $file) {
			$infos = pathInfo($path.$file);
			if (isset($infos['extension']) && $infos['extension'] == $extension) {
				$diff = str_replace(_PS_THEME_DIR_, '', $path);
				$this->files[] = _PS_BASE_URL_.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/'.$diff.'/'.$file;
			}
		}
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
		$content = "CACHE MANIFEST\n\n# Time: ".date('D M d Y H:i:s')."\n# Generated with the prestashop-appcache module https://github.com/romainberger/prestashop-appcache\n\nCACHE:\n";
		foreach ($this->files as $file) {
			$content .= $file."\n";
		}
		$content .= "\nNETWORK:\n*\n";

		$file = fopen(_PS_ROOT_DIR_.'/manifest.appcache', 'w');
		fwrite($file, $content);
		return fclose($file);
	}

}
