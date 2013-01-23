<?php

/**
 *  Prestashop Appcache
 *  https://github.com/romainberger/prestashop-appcache
 *
 *  @author Romain Berger <romain@romainberger.com>
 *  @version 0.1
 */

require _PS_MODULE_DIR_.'prestashopappcache/controllers/admin/Appcache.php';

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
     *  Automatically creates the manifest file when the htaccess is generated
     */
    public function hookActionHtaccessCreate($params) {
        $appcache = new Appcache;
        $appcache->generate();
    }

}
