<?php
/**
 * Created by PhpStorm.
 * User: Tavy
 * Date: 14.12.2013
 * Time: 23:11
 */
class My_Example {

    static protected $_instance;
    static protected $selection=1;

    public function __construct(){

    }
    public function setSelection($selection)
    {
        self::$selection = $selection;
        return self::$selection;
    }

    public function getSelection()
    {
        return self::$selection;
    }

    static public function getInstance(){
        if( is_null( self::$_instance) ){
            //      print "Instanta Noua !\n";
            self::$_instance = new My_Example();
        }
        return self::$_instance;

    }

    public function ShowMenu($selection){
        My_Example::setSelection($selection);
        echo '
        <div class="col-md-3 m-menu">
        <h3>Utilizatori</h3>
        <ul class="nav nav-pills nav-stacked">';

        $sel1="";
        $sel2="";
        $sel3="";
        switch (self::$selection){
            case self::$selection=1:
                $sel1="active";
                break;
            case self::$selection=2:
                $sel2="active";
                break;
            case self::$selection=3:
                $sel3="active";
                break;

        }

        echo'
            <li class="'.$sel1.'"><a href="#">Lista</a></li>
            <li class="'.$sel2.'"><a href="/user/add">Adaugare</a></li>
            <li class="'.$sel3.'"><a href="">Stergere</a></li>
        </ul>
    </div>

    ';



    }
}