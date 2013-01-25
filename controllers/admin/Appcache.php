<?php

/**
 *  Prestashop Appcache
 *  https://github.com/romainberger/prestashop-appcache
 *
 *  @author Romain Berger <romain@romainberger.com>
 *  @version 0.1
 */

class Appcache {

    private $files;

    /**
     *  Function to generate the manifest file
     *
     *  @return boolean
     */
    public function generate() {
        // css
        if (Configuration::get('PS_CSS_THEME_CACHE')) {
            $this->parseDirectory(_PS_THEME_DIR_.'cache', array('css'));
        }
        else {
            $this->parseDirectory(_PS_THEME_DIR_.'css', array('css'));
        }

        // js
        if (Configuration::get('PS_JS_THEME_CACHE')) {
            $this->parseDirectory(_PS_THEME_DIR_.'cache', array('js'));
        }
        else {
            $this->parseDirectory(_PS_THEME_DIR_.'js', array('js'));
        }

        // img
        $this->parseDirectory(_PS_THEME_DIR_.'img', array('png', 'gif', 'jpg'));

        $this->addAttribute();
        $this->addMimeType();

        return $this->write();
    }

    /**
     *  Parse a directory to get all the filenames
     *
     *  @param string $path Path to the directory
     *  @param array $extension Extensions of the files to keep
     */
    public function parseDirectory($path, $extension) {
        $files = scandir($path);
        $result = array();

        // @TODO parse sub directories
        // filter the files to only return the type wanted
        foreach ($files as $file) {
            $infos = pathInfo($path.$file);
            if (isset($infos['extension']) && in_array($infos['extension'], $extension)) {
                $diff = str_replace(_PS_THEME_DIR_, '', $path);
                $this->files[] = _PS_BASE_URL_.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/'.$diff.'/'.$file;
            }
        }
    }

    /**
     *  Make sure the appcache attribute is in the html tag
     */
    public function addAttribute() {
        $filename = _PS_THEME_DIR_.'header.tpl';
        if (!file_exists($filename)) {
            return false;
        }

        $content = file_get_contents($filename);
        // check if the atttribute is here
        $attr = '/manifest="manifest.appcache"/';
        preg_match($attr, $content, $matches);
        if ($matches) {
            return true;
        }
        else {
            // I nailed this regex at first try. Unfuckingbelievable
            $pattern = '/<html (.*?)>/i';
            $content = preg_replace($pattern, '<html $1 manifest="manifest.appcache">', $content);

            $file = fopen($filename, 'w');
            fwrite($file, $content);

            return fclose($file);
        }
    }

    /**
     *  Write the file
     */
    public function write() {
        $content = "CACHE MANIFEST\n\n";
        $content .= "# Time: ".date('D M d Y H:i:s')."\n";
        $content .= "# Generated with the prestashop-appcache module https://github.com/romainberger/prestashop-appcache\n\nCACHE:\n";

        foreach ($this->files as $file) {
            $content .= $file."\n";
        }

        $content .= "\nNETWORK:\n*\n";

        // if (Configuration::get('OFFLINE_PAGE')) {
        //     $content .= "\nFALLBACK\n";
        //     $content .= "/offline.html";
        // }

        $file = fopen(_PS_ROOT_DIR_.'/manifest.appcache', 'w');
        fwrite($file, $content);

        return fclose($file);
    }

    /**
     *  Triggered if the application cache is disabled
     *  Remove the manifest file and the manifest attribute
     */
    public function disable() {
        if (file_exists(_PS_ROOT_DIR_.'/manifest.appcache')) {
            unlink(_PS_ROOT_DIR_.'/manifest.appcache');
        }

        $filename = _PS_THEME_DIR_.'header.tpl';
        if (!file_exists($filename)) {
            return false;
        }

        $content = file_get_contents($filename);
        // check if the atttribute is here
        $attr = '/manifest="manifest.appcache"/';
        preg_match($attr, $content, $matches);
        if ($matches) {
            $pattern = '/<html (.*?) (manifest="manifest.appcache")>/i';
            $content = preg_replace($pattern, '<html $1>', $content);

            $file = fopen($filename, 'w');
            fwrite($file, $content);

            return fclose($file);
        }
    }

    /**
     *  Make sure the htaccess file contains the proper mime type
     */
    public function addMimeType() {
        $filename = _PS_ROOT_DIR_.'/.htaccess';
        if (!file_exists($filename)) {
            return false;
        }

        $content = file_get_contents($filename);
        // check if the mime type is here
        $mimeType = '/AddType text\/cache-manifest .appcache/';
        preg_match($mimeType, $content, $matches);
        if ($matches) {
            return true;
        }
        else {
            $file = fopen($filename, 'a');
            fwrite($file, "\n\nAddType text/cache-manifest .appcache\n");
            return fclose($file);
        }
    }

}
