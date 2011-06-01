<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    echo $this->Form->create('User', array('action' => 'login'));
    echo $this->Form->input('name', array('label' => 'Username')); // has a label element
    echo $this->Form->input('password'); // No div, no label
    echo $this->Form->end('Finish');

?>
