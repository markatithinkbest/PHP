<?php

// filename: task_get_game_id.php

//http://www.ithinkbest.com/b253-stateid-definition/

//B253 stateId definition

/**
* === preparation ===
* -3 (default) waiting for another player to join this game
* -2 two more to submit their number set
* -1 one more to submit their number set
* 0 ready to start game
*
 * 
 */
//define("STATE_WAIT_FOR_JOIN",     -3);
//define("STATE_2_MORE_NUMBER_SET",     -2);
//define("STATE_1_MORE_NUMBER_SET",     -1);
//define("STATE_0_READY_TO_START",     0);

$state_newly_open_wait_for_join=  -3;
$state_join_game_2_more_number_set=-2;
$state_1_more_number_set=-1;
$state_ready_to_start_game=0;


require ('task_get_game_id_pre.php');
$db = new Database();
$db->connect();

// list all open games
$db->select('game_header', 'game_id,p1_id,state_id', NULL, 'state_id<99', NULL);
if ($db->numRows() > 0) {
    // ### Some open games ###
    $where_clause = "(p1_id=$bin_id OR p2_id=$bin_id) AND state_id<99";
    $db->select('game_header', 'game_id,p1_id,p2_id,state_id', NULL, $where_clause, NULL);
    $sql = $db->getSql();
    //echo $sql; // to debug $where_clause is necessary

    $res = $db->getResult();
    //print_r($res); // to have a look on the result
    if ($db->numRows() > 0) {
        // === 1-1 existing open game, with player invloved, to continue
        $arr = array('ans' => 'yes',
            'game_id' => $res[0]['game_id'],
            'p1_id' => $res[0]['p1_id'],
            'p2_id' => $res[0]['p2_id'],
            'state_id' => $res[0]['state_id'],
            'desc' => 'to continue unfinished game');
    } else {
        $game_id = $res[0]['game_id'];
        $p1_id = $res[0]['p1_id'];
        $set_clause = array('p2_id' => $bin_id, 'state_id' => $state_join_game_2_more_number_set);
        $where_clause = "game_id=$game_id";
        $db->update('game_header', $set_clause, $where_clause); // Table name, column names and respective values
        if ($db->numRows() > 0) {
            // === 1-1-1 existing open, without player invloved, open a new one      
            $arr = array('ans' => 'yes',
                'game_id' => $game_id,
                'p1_id' => $p1_id,
                'p2_id' => $bin_id,
                'state_id' => $state_join_game_2_more_number_set,
                'desc' => 'join an open game, ###GCM to inform p1');

            // to triger gcm here
        } else {
            // === 1-1-2 unexcepted situation
            $arr = array('ans' => 'no', 'desc' => '1-1-2 unexcepted situation, SOMETHING IS WRONG HERE, TO DEBUG!');
        }
    }
} else {
    // ### No open game ###
    // === 1-2 no open game, create a new one
    $db->insert('game_header', array('game_id' => NULL, 'p1_id' => $bin_id));
    if ($db->numRows() > 0) {
        $res = $db->getResult();
        $arr = array('ans' => 'yes', 
            'game_id' => $res[0], 
            'p1_id' => $bin_id, 
            'p2_id' => 0, 
            'state_id' => $state_newly_open_wait_for_join);
    } else {
        // === 1-2-2 unexcepted situation
        $arr = array('ans' => 'no', 'desc' => '1-2-2 unexcepted situation SOMETHING IS WRONG HERE, TO DEBUG!');
    }
}
echo json_encode($arr);
