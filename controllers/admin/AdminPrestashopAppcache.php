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

        $this->fields_options = array(
            'appcache' => array(
                'title' =>  $this->l('Application Cache'),
                'icon' =>   'tab-orders',
                'top' => '',
                'bottom' => '',
                'fields' => array(
                    'OFFLINE_PAGE' => array(
                        'title' => $this->l('Offline page'),
                        'show' => true,
                        'type' => 'radio',
                        'choices' => array('on' => $this->l('Enable offline page'), 'off' => $this->l('Disable offline page'))
                    ),
                    'IGNORE_DIRECTORY' => array(
                        'title' => $this->l('Directories to ignore'),
                        'show' => true,
                        'type' => 'text'
                    )
                ),
                'submit' => array('name' => 'generateAppcache')
            )
        );

        parent :: __construct();
    }

    public function initToolbar() {
        parent::initToolbar();
        unset($this->toolbar_btn['save']);
    }

    public function postProcess() {
        if (isset($_POST['generateAppcache'])) {
            $appcache = new Appcache;
            $result = $appcache->generate();

            if (!$result) {
                $this->errors[] = Tools::displayError('An error occured while trying to generate the appcache.');
            }
        }
    }

}
