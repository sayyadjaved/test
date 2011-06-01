<?php

class UsersController extends AppController {

    var $name = 'Users';

    function beforeFilter() {
        parent::beforeFilter();

        $this->log('In Users controller');
    }

    function __sendMail($data, $email=null, $template=null, $subject=null) {
       
        //send mail to admin about new user registration
        if ($this->send_SMTP_mail(array('from' => 'noreply@bhej.de', 'fromName' => 'Bhejde',
                    'to' => $email, 'template' => $template,
                    'subject' => $subject, 'toName' => $data['User']['username'],
                    'data' => $data))) {

            $this->log("Mail sent to user having email $email");
            return 'success';
        } else {

            $this->log("Error sending mail to user having email $email");
            return 'error';
        }
        return $message;
    }

    function login() {

        $email = '';
        $password = '';

        extract($_POST);

        if ($email == '') {

            $message['email'] = 'empty';
            $error = true;
        }
        if ($password == '') {

            $message['password'] = 'empty';
            $error = true;
        }

        $this->log("Login request received for user with $email");

        $this->set(compact('message'));
    }

    function registration() {

        $data = $this->data;
        $email = $data['User']['email'];
        $password = $data['User']['password'];
        $name = $data['User']['username'];

        if ($email == null) {
            $message['email'] = 'empty'; //if email is not received
            $error = true;
        }
        if ($password == null) {
            $message['password'] = 'empty'; //if password is not received
            $error = true;
        }
        if ($name == null) {
            $message['name'] = 'empty'; //if password is not received
            $error = true;
        }


        if (!empty($email)) {   //if both are received
            $this->log("New request for registration with email $email and userrname $name");

            $this->log("Email is valid $email");
            $this->log('User is new');
            $data['User']['email'] = trim($email);
            $data['User']['username'] = trim($name);

            $this->User->create();

            if ($this->User->save($data)) { //save the details
                //$this->log("Auth code to be saved is :". $data['User']['authentication_code']."");
                $this->log("Registration saved for user having email $email");
                $this->log("Registration saved for user having email $email and user id : " . $this->User->id . "");
                $message['status'] = 'saved';

                //$this->log("Hash for mail is $hash");
                $data['User']['name'] = $name;
                $template = 'activation_mail';
                $subject = 'Bhej.de Account Activation';
                $message['mail'] = $this->__sendMail($data, $email, $template, $subject);
              
            } else {

                $message['status'] = 'error'; //if error saving data
                $this->log("Error for saving registration having email $email");
            }
        }



        $this->set(compact('message'));
    }

    function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->User->create();
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__('The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
            }
        }
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__('The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->User->read(null, $id);
        }
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for user', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__('User deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>