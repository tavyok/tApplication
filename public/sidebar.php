<?php
/**
 * Created by PhpStorm.
 * User: Tavy
 * Date: 15.08.2014
 * Time: 03:17
 */

if ($this->identity["role"]==Table_User::ROLE_USER){
    echo $this->partial('_partials/user-menu.phtml',
        array(
            'identity' => $this->identity,
            'action' => $this->action
        ));
}
if ($this->identity["role"]==Table_User::ROLE_ADMIN){
    echo $this->placeholder('sidebar');
}
?>