<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    echo $this->Form->create('User', array('action' => 'registration'));
    echo $this->Form->input('username'); // has a label element
    echo $this->Form->input('email');
    echo $this->Form->input('password'); // No div, no label
    echo $this->Form->end('Finish');

?>
