<?php

/**
 * 檔案名稱：game.php
 * 主要功能：
 * 提供Ｂ００１處理對局時需要用的基本功能，最基本功能是開新局和加入對局。
 * 同時基本查詢以得知指定玩家還有那些對局還沒有完成。
 *
 * @author Mark Chen, 2015-5-10,台中
 * 
 */
/**
 * 底層增刪改查功能使用以下class
  //https://github.com/rorystandley/MySQLi-CRUD-PHP-OOP
 */
require_once ('db.php');

class GameB001 {

    private $game_table = "game_header";

    /**
     * 這個player還沒有完成的game數量
     * @param type $player
     * @return type
     */
    function getUnfinishedGameCnt($player) {
        $db = new Database();
        $db->connect();

        //
        $where_clause = "(p1_id=$player OR p2_id=$player) AND state_id<99";
        $db->select('game_header', '*', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        return count($res);
    }

    /**
     * 以指定玩家取消指定對局
     * @param type $player
     * @param type $game
     * @return type　１：成功　０：失敗（重覆第二次以後算是失敗）
     * ?
     */
    function cancelPlayerGame($player, $game) {
        $db = new Database();
        $db->connect();
        //
        $state_id = "3000000" + $player;
        $set_array = array('state_id' => $state_id);
        $where_clause = "game_id=$game";
        $db->update($this->game_table, $set_array, $where_clause);
        $res = $db->getResult();
        return count($res[0]);
    }

    /**
     * 　取得指定對局的基本信息，如果該對局不存在，返回陣列的對局編號為０。 
     * @param type $game_id
     * @return type
     */
    function getGame($game_id) {
        $db = new Database();
        $db->connect();
        //
        $where_clause = "game_id=$game_id";
        $db->select($this->game_table, '*', NULL, $where_clause, '');
// Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        //echo $db->getSql();
        if (count($res) == 0) {
            $result = array('game_id' => 0);
        } else {
            $result = $res[0];
        }
        return $result;
    }

    /**
     * 取得玩家一個未完成的的基本信息，如果玩家沒有任何未完成對局，返回陣列的對局編號為０。
     * ＊＊＊注意，開發人員要負責確保任何玩家只有最多只有一個未完成的對局＊＊＊
     * @param type $player
     * @return type
     */
    function getUnfinishedGame($player) {
        $db = new Database();
        $db->connect();
        //
        $where_clause = "(p1_id=$player OR p2_id=$player) AND state_id<99";
        $db->select($this->game_table, '*', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        if (count($res) == 0) {
            $result = array('game_id' => 0);
        } else {
            $result = $res[0];
        }
        return $result;
    }

    /**
     * 開新局，返回該新局的基礎信息
     * @param type $player
     * @param type $set
     * @return type
     */
    function openNewGame($player, $set) { //p as player       
        $db = new Database();
        $db->connect();
        //
        $db->insert($this->game_table, array('p1_id' => $player, 'p1_set' => $set));
        $res = $db->getResult();
        $game_id = $res[0];
        return $this->getGame($game_id);
    }

    /**
     * 加入對局，返回加入對局的基礎信息
     * @param type $player
     * @param type $game
     * @return type
     */
    function joinOpenGame($player, $game) { //p as player       
        $db = new Database();
        $db->connect();
        //
        $set_array = array('p2_id' => $player,'state_id' => 0);
        $where_clause = "game_id=$game";
        $db->update($this->game_table, $set_array, $where_clause);
        $res = $db->getResult();
        return $this->getGame($game);
    }
    
    
    function getOpenGameId() { //p as player       
        $db = new Database();
        $db->connect();
        //
        $where_clause = "state_id=-1";
        $db->select($this->game_table, '*', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
        $res = $db->getResult();
        if (count($res) == 0) {
            $result = 0;
        } else {
            $arr = $res[0];
            $result=$arr['game_id'];
        }

//        print_r($result);
        return $result;
    }

}

/**
 * 
 * @return type
 */
//    function getOpenGameId() { //p as player       
//        $db = new Database();
//        $db->connect();
//        //
//        $where_clause = "state_id=-1";
//        $db->select($this->game_table, '*', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
//        $res = $db->getResult();
//        if (count($res) == 0) {
//            $result =0;
//        } else {
//            $result = $res[0];
//        }
//        
//        print_r($result)
//        return $result;
//    }
//SAMPLE USAGE
//$g001 = new GameB001();
//$a = 30;
//
//echo "<h2>player $a, getUnfinishedGameCnt</h2>";
//echo $g001->getUnfinishedGameCnt($a);
//
//echo "<h2>player $a, getUnfinishedGame </h2>";
//echo json_encode($g001->getUnfinishedGame($a));
//
//$a = 2;
//echo "<h2>player $a, openNewGame </h2>";
//echo json_encode($g001->openNewGame($a));
//
//
//$player = 33;
//$game = 140;
//
//echo "<h2>player $player, joinOpenGame #$game </h2>";
//echo json_encode($g001->joinOpenGame($player, $game));
//
//echo "<h2>player $player, cancelGame #$game </h2>";
//echo 'affected row cnt is ' . $g001->cancelPlayerGame($player, $game);
//
//class GameB002 extends GameB001 {
//
//    function getOpenGameId() { //p as player       
//        $db = new Database();
//        $db->connect();
//        //
//        $where_clause = "state_id=-1";
//        $db->select($this->game_table, '*', NULL, $where_clause, ''); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
//        $res = $db->getResult();
//        if (count($res) == 0) {
//            $result = 0;
//        } else {
//            $result = $res[0];
//        }
//
//        print_r($result)
//        return $result;
//    }
//
//}
