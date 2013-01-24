<?php

/**
 *  Prestashop Appcache
 *  https://github.com/romainberger/prestashop-appcache
 *
 *  @author Romain Berger <romain@romainberger.com>
 *  @version 0.1
 */

require _PS_MODULE_DIR_.'prestashopappcache/controllers/admin/Appcache.php';

class AdminPrestashopAppcacheController extends ModuleAdminController {

    public function __construct() {
        $this->className    = 'AdminPrestashopAppcache';
        $this->table = 'configuration';

        $fields = array(
            'ACTIVATE_APPCACHE' => array(
                'title' => $this->l('Application Cache'),
                'desc' => $this->l('Enable the application cache'),
                'cast' => 'intval',
                'type' => 'bool',
                'default' => '1'
            ),

            'OFFLINE_PAGE' => array(
                'title' => $this->l('Offline page'),
                'desc' => $this->l('Display dedicated page when the user is offline'),
                'cast' => 'intval',
                'type' => 'bool',
                'default' => '1'
            )
        );

        $this->fields_options = array(
            'general' => array(
                'title' =>  $this->l('Application Cache'),
                'image' => '../img/t/AdminAdminPreferences.gif',
                'fields' => $fields,
                'submit' => array('title' => $this->l('   Save   '), 'class' => 'button'),
            ),
        );

        parent :: __construct();
    }

    public function initToolbar() {
        parent::initToolbar();
        unset($this->toolbar_btn['save']);
    }

    public function postProcess() {
        $appcache = new Appcache;
        if (isset($_POST['generateAppcache']) && Configuration::get('ACTIVATE_APPCACHE')) {
            $result = $appcache->generate();

            if (!$result) {
                $this->errors[] = Tools::displayError('An error occured while trying to generate the appcache.');
            }
        }
        else if (isset($_POST['generateAppcache']) && !Configuration::get('ACTIVATE_APPCACHE')) {
            $appcache->disable();
        }

        parent::postProcess();
    }

}
