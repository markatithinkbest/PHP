<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * * https://github.com/rorystandley/MySQLi-CRUD-PHP-OOP
 */

/**
 * Description of game
 *
 * @author u1
 */
require_once ('class/db.php');
class GameB001 {

    private $game_table = "game_header";
    private $db; 

    /**
     * Programmer needs to ensure only one maximum record
     * @param type $bin_id
     * @return type
     */
    //http://php.net/manual/en/language.oop5.decon.php

    function __construct() {
        print "In BaseClass constructor\n";
        
        
        $db= new Database();
        $db->connect();
    }

    function getUnfinishedGame($bin_id) {
//        require_once ('class/db.php');
//        $db = new Database();
//        $db->connect();
        //
        //
        //
        $where_clause = "(p1_id=$bin_id OR p2_id=$bin_id) AND state_id<99";
        $db->select('game_header', 'game_id,p1_id,p1_set,p2_id,p2_set,state_id', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        if (count($res) == 0) {
            $result = array('game_id' => 0);
        } else {
            $result = $res[0];
        }
        return $result;
    }

    function openNewGame($bin_id) {
        require_once ('class/db.php');
        $db = new Database();
        $db->connect();
        //
        //
        //$db->insert('CRUDClass',array('name'=>'Name 5','email'=>$data)); 
        // Table name, column names and respective values

        $db->insert('CRUDClass', array('name' => 'Name 5', 'email' => $data));
        $res = $db->getResult();
    }

}

//SAMPLE USAGE
$game = new GameB001();
$bin_id = 30;
$feedback = $game->getUnfinishedGame($bin_id);
echo "<h2>bin_id=$bin_id, unfinished game ";
echo json_encode($feedback);
