<?php

/**
 *	Prestashop Appcache
 *	https://github.com/romainberger/prestashop-appcache
 *
 *	@author Romain Berger <romain@romainberger.com>
 *	@version 0.1
 */

class PrestashopAppcache extends Module {

	public function __construct() {
		$this->name = 'appcache';
		$this->tab = 'front_office_features';
		$this->version = '0.1';
		$this->author = 'Romain Berger';

		parent::__construct();

		$this->displayName = $this->l('Appcache');
		$this->description = $this->l('Generate an appcache manifest to make your site crazy fast.');
	}

	public function install() {
		if (!parent::install())
			return false;
		return true;
	}

}
