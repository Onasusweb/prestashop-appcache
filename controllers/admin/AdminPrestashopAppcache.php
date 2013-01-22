<?php

/**
 *  Prestashop Appcache
 *  https://github.com/romainberger/prestashop-appcache
 *
 *  @author Romain Berger <romain@romainberger.com>
 *  @version 0.1
 */

class AdminPrestashopAppcacheController extends ModuleAdminController {

    public function __construct() {
        $this->className    = 'AdminPrestashopAppcache';

        parent :: __construct();
    }

}
