<?php

require_once('lib/db_connect.inc.php');

/*--------------------------------------------------\
| function: init the club                           |
| 1. function one [2005-5-20 by chyd]               |
| 2. function two [2005-5-20 by chyd]               |
|                                                   |
|                                                   |
|                                                   |
\--------------------------------------------------*/

$seedarray =microtime(); 
$seedstr =split(" ",$seedarray,5); 
$seed =$seedstr[0]*10000; 
// step 2:
srand($seed); 
// setp 3:
$index = rand(60000, 99999);


/**
 * function: function one
 * 1. search the team whose club_id is null
 * 2. insert a new club whose name is the name of above team
 * 3. update the team's club_id to be the id of the new club
 **/
$query  = " select team_id, name as team_name ";
$query .= " from team ";
$query .= " where club_id is null ";

$rs = &$db->Execute($query);
if (!$rs) {
    print "Database error1." . $db->ErrorMsg();
	exit;
}
while (!$rs->EOF) {
    $primary_team_id = $rs->fields['team_id'];
	$team_name = $rs->fields['team_name'];
	
	++$index;
	
	$query  = " insert into club(club_id, name, user_id, nation_id) ";
	$query .= " values('$index', '$team_name', null, 1) ";
	$rs2 = &$db->Execute($query);
	if (!$rs2) {
		print "Database error2." . $db->ErrorMsg();
		exit;
	}
	
	$query  = " select club_id from club where club_id='$index' ";
	$rs2 = &$db->Execute($query);
	if (!$rs2) {
		print "Database error3." . $db->ErrorMsg();
		exit;
	}
	
	$primary_club_id = $rs2->fields['club_id'];
	
	$query  = " update team set club_id = '$primary_club_id' ";
	$query .= " where id='$primary_team_id' ";
	$rs2 = &$db->Execute($query);	
	if (!$rs2) {
		print "Database error4." . $db->ErrorMsg();
		exit;
	}
	
	$rs->MoveNext(); 
}

/**
 * function: function two
 * 1. find the team whose tactics has not been set
 * 2. set tactics of the above team
 **/
$query  = " SELECT team.team_id ";
$query .= " FROM team ";
$query .= " WHERE team.team_id NOT IN ";
$query .= "( ";
$query .= " SELECT team_id FROM tactics ";
$query .= " ) "; 
$rs = &$db->Execute($query);
if (!$rs) {
    print "Database error5." . $db->ErrorMsg();
	exit;
}
while (!$rs->EOF) {
    $db->BeginTrans();
	
    $team_id = $rs->fields['team_id'];
	if (!empty($team_id)) {
	    $query  = " SELECT player.player_id ";
		$query .= " FROM player ";
		$query .= " WHERE player.team_id='$team_id' ";
		$query .= " LIMIT 11 ";
		$rs2 = &$db->Execute($query);
		if (!$rs2) {
			print "Database error6." . $db->ErrorMsg();
			exit;
		}
		$index = 0;
		$f_442 = array(	
						0,1,0,1,0,
						0,0,0,0,0,
						1,1,0,1,1,
						0,0,0,0,0,
						1,1,0,1,1
						);
		while (!$rs2->EOF) {
		    while ($index < 25) {
			    if ($f_442[$index] == 1) {
				    $position_place = $index;
					break;
				}
				else {
				    ++$index;
					continue;
				}				    
			}
			if ($index == 25) $position_place = 25;
			
		    $player_id = $rs2->fields['player_id'];
			$query  = " INSERT INTO tactics ";
			$query .= " ( team_id, tactics_id, name, focus_passing, ";
			$query .= "   mentality, position_place, player_id ) ";
			$query .= " VALUES( '$team_id', '1', 'tactics_A', '1', ";
			$query .= " '2', '$position_place', '$player_id') ";
					
			$rs3 = &$db->Execute($query);	
			if (!$rs3) {
			    $db->RollbackTrans();
				print "Database error7." . $db->ErrorMsg();
				exit;
			}
			
			++$index;
			$rs2->MoveNext(); 
		}
	}
    
	$db->CommitTrans();
	
    $rs->MoveNext(); 
}

print "success!";



?>
