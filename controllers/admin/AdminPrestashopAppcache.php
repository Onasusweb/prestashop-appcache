<?php

/**
 *  Prestashop Appcache
 *  https://github.com/romainberger/prestashop-appcache
 *
 *  @author Romain Berger <romain@romainberger.com>
 *  @version 0.3
 */

require _PS_MODULE_DIR_.'prestashopappcache/controllers/admin/Appcache.php';

class AdminPrestashopAppcacheController extends ModuleAdminController {

    public function initFieldsetConfiguration() {
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Application cache configuration'),
                'image' => '../img/t/AdminAdminPreferences.gif'
            ),
            'desc' => $this->l(''),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Application Cache'),
                    'name' => 'APPCACHE_ACTIVATE',
                    'class' => 't',
                    'is_bool' => true,
                    // 'disabled' => Combination::isCurrentlyUsed(),
                    'values' => array(
                        array(
                            'id' => 'activate_1',
                            'value' => 1,
                            'label' => $this->l('Enable'),
                        ),
                        array(
                            'id' => 'activate_0',
                            'value' => 0,
                            'label' => $this->l('Disable')
                        )
                    ),
                    'desc' => $this->l('Enable the application cache'),
                ),

                array(
                    'type' => 'radio',
                    'label' => $this->l('Offline page'),
                    'name' => 'APPCACHE_OFFLINE_PAGE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'offline_1',
                            'value' => 1,
                            'label' => $this->l('Enable')
                        ),
                        array(
                            'id' => 'activate_0',
                            'value' => 0,
                            'label' => $this->l('Disable')
                        )
                    ),
                    'desc' => $this->l('Display a dedicated page when the user is offline')
                )
            ),
            'submit' => array(
                'title' => $this->l('   Save   '),
                'class' => 'button'
            )
        );

        $this->fields_value['APPCACHE_ACTIVATE'] = Configuration::get('APPCACHE_ACTIVATE');
        $this->fields_value['APPCACHE_OFFLINE_PAGE'] = Configuration::get('APPCACHE_OFFLINE_PAGE');
    }

    public function initFieldsetDirectories() {
        $this->fields_form[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Directories and extensions'),
                'image' => '../img/t/AdminAdminPreferences.gif'
            ),
            'desc' => $this->l(''),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Extensions'),
                    'name' => 'EXTENSIONS',
                    'size' => 100,
                    'desc' => $this->l('List the extensions to add, seperated by commas')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Directories to add'),
                    'name' => 'DIRECTORIES_ADD',
                    'size' => 100,
                    'desc' => $this->l('List the directories to add, seperate by commas')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Directories to ignore'),
                    'name' => 'DIRECTORIES_IGNORE',
                    'size' => 100,
                    'desc' => $this->l('List the directories to ignore')
                ),
            )
        );

        $this->fields_value['EXTENSIONS'] = Configuration::get('APPCACHE_EXTENSIONS');
        $this->fields_value['DIRECTORIES_ADD'] = Configuration::get('APPCACHE_DIRECTORIES_ADD');
        $this->fields_value['DIRECTORIES_IGNORE'] = Configuration::get('APPCACHE_DIRECTORIES_IGNORE');
    }

    public function renderForm() {
        $this->initFieldsetConfiguration();
        $this->initFieldsetDirectories();
        $this->multiple_fieldsets = true;

        return parent::renderForm();
    }

    public function initContent() {
        $this->initToolbar();
        $this->content .= $this->renderForm();

        $this->context->smarty->assign(array(
            'content' => $this->content,
        ));
    }

    public function initToolbar() {
        $this->toolbar_btn['save'] = array(
            'href' => '#',
            'desc' => $this->l('Save')
        );
    }

    public function postProcess() {
        if (isset($_POST) && !empty($_POST)) {
            // Update configuration
            Configuration::updateValue('APPCACHE_ACTIVATE', Tools::getValue('APPCACHE_ACTIVATE'));
            if (Configuration::get('APPCACHE_ACTIVATE')) {
                Configuration::updateValue('APPCACHE_OFFLINE_PAGE', Tools::getValue('APPCACHE_OFFLINE_PAGE'));
            }
            else {
                Configuration::updateValue('APPCACHE_OFFLINE_PAGE', 0);
            }

            Configuration::updateValue('APPCACHE_EXTENSIONS', $this->processStringList(Tools::getValue('EXTENSIONS')));
            Configuration::updateValue('APPCACHE_DIRECTORIES_ADD', $this->processStringList(Tools::getValue('DIRECTORIES_ADD')));
            Configuration::updateValue('APPCACHE_DIRECTORIES_IGNORE', $this->processStringList(Tools::getValue('DIRECTORIES_IGNORE')));
        }

        $appcache = new Appcache;

        if (Configuration::get('APPCACHE_ACTIVATE')) {
            $result = $appcache->generate();
            if (!$result) {
                $this->errors[] = Tools::displayError('An error occured while trying to generate the appcache.');
            }
        }
        else {
            $appcache->disable();
        }
    }

    /**
     *  Clean a string of item separated by commas
     *
     *  @param string $string String to process
     *  @return string
     */
    public function processStringList($list) {
        $array =  explode(',', $list);
        $result = '';

        for($i = 0; isset($array[$i]); $i++){
            if(preg_match('/[\s\S]/', trim($array[$i]))){
                $result .= $array[$i].',';
            }
        }

        $result = substr($result, 0, strlen($result) - 1);

        return $result;
    }

}
