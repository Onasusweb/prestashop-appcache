<?php

/**
 *  Prestashop Appcache
 *  https://github.com/romainberger/prestashop-appcache
 *
 *  @author Romain Berger <romain@romainberger.com>
 *  @version 0.1
 */

class PrestashopAppcache extends Module {

    public function __construct() {
        $this->name = 'prestashopappcache';
        $this->tab = 'front_office_features';
        $this->version = '0.1';
        $this->author = 'Romain Berger';

        parent::__construct();

        $this->displayName = $this->l('Prestashop Appcache');
        $this->description = $this->l('Generate an appcache manifest to make your site crazy fast.');
    }

    public function install() {
        $tab = new Tab();
        $tab->name = 'Application Cache';
        $tab->module = 'prestashopappcache';
        $tab->class_name = 'AdminPrestashopAppcache';
        $tab->id_parent = 17;
        $tab->add();

        Configuration::updateValue('OFFLINE_PAGE', 0);

        if (!parent::install()
            || !$this->registerHook('actionHtaccessCreate'))
            return false;
        return true;
    }

    public function uninstall() {
        $tabId = Tab::getIdFromClassName('AdminPrestashopAppcache');
        $tab = new Tab($tabId);
        $tab->delete();
        parent::uninstall();

        return true;
    }

    /**
     *  Add the manifest appcache mime type to htaccess
     */
    public function hookActionHtaccessCreate($params) {
        $file = fopen(_PS_ROOT_DIR_.'/.htaccess', 'a');
        fwrite($file, "\n\nAddType text/cache-manifest .appcache\n");
        fclose($file);
    }

}
