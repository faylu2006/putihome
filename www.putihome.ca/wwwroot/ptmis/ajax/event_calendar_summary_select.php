<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
    
	$fdate 	= mktime(0,0,0, date("m") ,date("d"), date("Y"));
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();

	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "start_date >= '" . $sd . "' AND start_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "start_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "start_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	
	if( $_REQUEST["sites"] != "") {
		$ccc .= ($ccc!=""?" AND ":"") . "c.site IN (" . $_REQUEST["sites"] . ")";
	} else {
		$ccc .= ($ccc!=""?" AND ":"") . "c.site IN (-1)";
	}

	if( $_REQUEST["branchs"] != "") {
		$ccc .= ($ccc!=""?" AND ":"") . "c.branch IN (" . $_REQUEST["branchs"] . ")";
	} else {
		$ccc .= ($ccc!=""?" AND ":"") . "c.branch IN (-1)";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	$query0 = "SELECT c.id 
					FROM event_calendar c
					WHERE deleted <> 1 
						  $ccc   
					ORDER BY start_date ASC";

	$result0 = $db->query($query0);
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		
		$evt_id 	= $row0["id"];

		$query1 	= "SELECT a.id, a.title, a.status, a.start_date, a.end_date, b.title as site_desc 
								FROM event_calendar a
			 					INNER JOIN puti_sites b ON (a.site = b.id) 
								WHERE a.deleted <> 1 AND a.id = '" . $evt_id . "'";
		
		$result1	= $db->query($query1);
		$row1 		= $db->fetch($result1);

		$evt_arr["id"] 				= $row1["id"];
		$evt_arr["title"] 			= cTYPE::gstr($words[strtolower($row1["site_desc"])]) . ' - ' . cTYPE::gstr($row1["title"]);
		$evt_arr["start_date"] 		= $row1["start_date"]>0?date("Y-m-d", $row1["start_date"]):'';
		$evt_arr["end_date"] 		= $row1["end_date"]>0?date("Y-m-d", $row1["end_date"]):'';
		$evt_arr["date_range"] 		= $evt_arr["start_date"] . ($evt_arr["end_date"]!=''?' ~ ' . $evt_arr["end_date"]:'');
		$evt_arr["status"] 			= $row1["status"];

		$query2 	= "SELECT 
								count(a.id) as total,  
								sum(if(b.gender='Male',1,0)) as menro, 
								sum(if(b.gender='Female' || b.gender='',1,0)) as fenro,
								
								sum(if(b.gender='Male',unauth,0)) as munauth, 
								sum(if(b.gender='Female' || b.gender='',unauth,0)) as funauth, 
								sum(unauth) as tunauth,
	
								sum(if(b.gender='Male',trial,0)) as mtrial, 
								sum(if(b.gender='Female' || b.gender='',trial,0)) as ftrial, 
								sum(trial) as ttrial,
							
								sum(if(b.gender='Male',new_flag,0)) as mnew, 
								sum(if(b.gender='Female' || b.gender='',new_flag,0)) as fnew, 
								sum(new_flag) as tnew,
								
								sum(if(b.gender='Male',graduate,0)) as mgrad, 
								sum(if(b.gender='Female' || b.gender='',graduate,0)) as fgrad, 
								sum(graduate) as tgrad,

								sum(if(b.gender='Male',cert,0)) as mcert, 
								sum(if(b.gender='Female' || b.gender='',cert,0)) as fcert,
								sum(cert) as tcert
						
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
						WHERE a.deleted <> 1 AND  b.deleted <> 1 AND 
							  a.event_id = '" . $evt_id . "'";
		
		$result2	= $db->query($query2);
		$row2 		= $db->fetch($result2);
		
		$evt_arr["total"] 			= $row2["total"]==""?0:$row2["total"];
		$evt_arr["menro"] 			= $row2["menro"]==""?0:$row2["menro"];
		$evt_arr["fenro"] 			= $row2["fenro"]==""?0:$row2["fenro"];

		$evt_arr["munauth"] 		= $row2["munauth"]==""?0:$row2["munauth"];
		$evt_arr["funauth"] 		= $row2["funauth"]==""?0:$row2["funauth"];
		$evt_arr["tunauth"] 		= $row2["tunauth"]==""?0:$row2["tunauth"];
	
		$evt_arr["mtrial"] 			= $row2["mtrial"]==""?0:$row2["mtrial"];
		$evt_arr["ftrial"] 			= $row2["ftrial"]==""?0:$row2["ftrial"];
		$evt_arr["ttrial"] 			= $row2["ttrial"]==""?0:$row2["ttrial"];

		$evt_arr["mnew"] 			= $row2["mnew"]==""?0:$row2["mnew"];
		$evt_arr["fnew"] 			= $row2["fnew"]==""?0:$row2["fnew"];
		$evt_arr["tnew"] 			= $row2["tnew"]==""?0:$row2["tnew"];

		$evt_arr["mgrad"] 			= $row2["mgrad"]==""?0:$row2["mgrad"];
		$evt_arr["fgrad"] 			= $row2["fgrad"]==""?0:$row2["fgrad"];
		$evt_arr["tgrad"] 			= $row2["tgrad"]==""?0:$row2["tgrad"];

		$evt_arr["mcert"] 			= $row2["mcert"]==""?0:$row2["mcert"];
		$evt_arr["fcert"] 			= $row2["fcert"]==""?0:$row2["fcert"];
		$evt_arr["tcert"] 			= $row2["tcert"]==""?0:$row2["tcert"];

		$evt[$cnt0]					= $evt_arr;

		$cnt0++;
	}

	$query2 	= "SELECT 
							count(a.id) as total,  
							sum(if(b.gender='Male',1,0)) as menro, 
							sum(if(b.gender='Female' || b.gender='',1,0)) as fenro,
							
							sum(if(b.gender='Male',unauth,0)) as munauth, 
							sum(if(b.gender='Female' || b.gender='',unauth,0)) as funauth, 
							sum(unauth) as tunauth,

							sum(if(b.gender='Male',trial,0)) as mtrial, 
							sum(if(b.gender='Female' || b.gender='',trial,0)) as ftrial, 
							sum(trial) as ttrial,
							
							sum(if(b.gender='Male',new_flag,0)) as mnew, 
							sum(if(b.gender='Female' || b.gender='',new_flag,0)) as fnew, 
							sum(new_flag) as tnew,
							
							sum(if(b.gender='Male',graduate,0)) as mgrad, 
							sum(if(b.gender='Female' || b.gender='',graduate,0)) as fgrad, 
							sum(graduate) as tgrad,

							sum(if(b.gender='Male',cert,0)) as mcert, 
							sum(if(b.gender='Female' || b.gender='',cert,0)) as fcert,
							sum(cert) as tcert
					
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id) 
					INNER JOIN event_calendar c ON (a.event_id = c.id) 
					WHERE a.deleted <> 1 AND c.deleted <> 1 AND b.deleted <> 1  
						  $ccc";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	$grand = array();
	$grand["total"] 			= $row2["total"]==""?0:$row2["total"];
	$grand["menro"] 			= $row2["menro"]==""?0:$row2["menro"];
	$grand["fenro"] 			= $row2["fenro"]==""?0:$row2["fenro"];

	$grand["munauth"] 			= $row2["munauth"]==""?0:$row2["munauth"];
	$grand["funauth"] 			= $row2["funauth"]==""?0:$row2["funauth"];
	$grand["tunauth"] 			= $row2["tunauth"]==""?0:$row2["tunauth"];

	$grand["mtrial"] 			= $row2["mtrial"]==""?0:$row2["mtrial"];
	$grand["ftrial"] 			= $row2["ftrial"]==""?0:$row2["ftrial"];
	$grand["ttrial"] 			= $row2["ttrial"]==""?0:$row2["ttrial"];

	$grand["mnew"] 				= $row2["mnew"]==""?0:$row2["mnew"];
	$grand["fnew"] 				= $row2["fnew"]==""?0:$row2["fnew"];
	$grand["tnew"] 				= $row2["tnew"]==""?0:$row2["tnew"];

	$grand["mgrad"] 			= $row2["mgrad"]==""?0:$row2["mgrad"];
	$grand["fgrad"] 			= $row2["fgrad"]==""?0:$row2["fgrad"];
	$grand["tgrad"] 			= $row2["tgrad"]==""?0:$row2["tgrad"];

	$grand["mcert"] 			= $row2["mcert"]==""?0:$row2["mcert"];
	$grand["fcert"] 			= $row2["fcert"]==""?0:$row2["fcert"];
	$grand["tcert"] 			= $row2["tcert"]==""?0:$row2["tcert"];
	

	$response["data"]["evt"] 	= $evt;
	$response["data"]["grand"] 	= $grand;
	$response["errorMessage"]	= "";
	$response["errorCode"] 		= 0;

	echo json_encode($response);
} catch(cERR $e) {
	echo json_encode($e->detail());
	
} catch(Exception $e ) {
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}



?>
