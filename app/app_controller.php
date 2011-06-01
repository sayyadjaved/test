<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {

    var $components = array('Email', 'SwiftMailer', 'Session');
    var $helpers = array('Text', 'Html', 'Form', 'Ajax', 'Time', 'Number', 'Session');

    function setPageDetails($layout = 'default', $page_title = '', $menu_active = 'home', $hideTopLinks = false) {

        $this->layout = $layout;
        $this->set('title_for_layout', 'Bhejde - ' . $page_title);
        $this->pageTitle = $page_title;
        //activate the menu
        $this->set('menuactive', $menu_active);
        $this->set('hideTopLinks', $hideTopLinks);
    }

    function send_SMTP_mail($details, $url = '') {

        $this->SwiftMailer->from = $details['from'];
        $this->SwiftMailer->fromName = $details['fromName'];
        $this->SwiftMailer->to = $details['to'];
        $this->SwiftMailer->toName = $details['toName'];

        $this->set('data', $details['data']);

        if ($details['from'] == null) {
            return false;
        } elseif ($details['to'] == null) {
            return false;
        } elseif ($details['subject'] == null) {
            return false;
        } elseif (!$this->SwiftMailer->send($details['template'], $details['subject'], $url)) {
            $this->log('Error sending email "$template".', LOG_ERROR);
            return false;
        } else {
            return true;
        }
    }

    function setLayout($response_type) {

        if ($response_type == 'xml') {

            $this->layout = 'xml/add';
        } else {
            $this->layout = 'json/default';
        }
    }

    function requestfolder($folder_name, $path = null){

        if(!$path){
            $path = WWW_ROOT."files/";
        }
        // setup dir names absolute and relative
        $folder_url = $path.$folder_name;  //create request inside the user folder

        // create the folder if it does not exist
        if(!is_dir($folder_url)) {

            mkdir($folder_url, 01777);

            //change the chmod of medium project directory
            chmod($folder_url, 0777);

            $this->log("Folder $folder_name has been created inside $path");
            return true;
        }
    }

}