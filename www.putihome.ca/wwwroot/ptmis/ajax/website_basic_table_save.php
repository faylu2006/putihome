<?php 
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["rid"] 				= '{"type":"NUMBER", 	"length":11, 	"id": "rid", 			"name":"ID", 		    "nullable":0}';
	$type["title_en"] 		    = '{"type":"CHAR", 		"length":255, 	"id": "lang_key", 	    "name":"Title EN", 	    "nullable":0}';
	$type["title_cn"] 		    = '{"type":"CHAR", 		"length":255, 	"id": "title_cn", 	    "name":"Title CN", 	    "nullable":0}';
	$type["desc_en"] 		    = '{"type":"CHAR", 		"length":1023, 	"id": "desc_en", 	    "name":"Description English", 	"nullable":1}';
	$type["desc_cn"] 		    = '{"type":"CHAR", 		"length":1023, 	"id": "desc_cn", 	    "name":"Description Chinese", 	"nullable":1}';
	$type["table_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "table_name", 	"name":"Table Name", 	"nullable":0}';
	$type["status"] 		    = '{"type":"NUMBER", 	"length":1, 	"id": "status", 	    "name":"status", 	    "nullable":0}';
	$type["sn"]				    = '{"type":"NUMBER", 	"length":6, 	"id": "sn", 		    "name":"SN", 	        "nullable":1}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
    $fields = array();
    $rid                    = $_REQUEST["rid"];
    $fields["title_en"]	    = $_REQUEST["title_en"];
    $fields["title_cn"]	    = $_REQUEST["title_cn"];
    $fields["desc_en"]	    = $_REQUEST["desc_en"];
    $fields["desc_cn"]	    = $_REQUEST["desc_cn"];
    $fields["filter"]	    = strtolower($_REQUEST["table_name"]);
    $fields["status"]	    = $_REQUEST["status"];
    $fields["sn"]	        = $_REQUEST["sn"]?$_REQUEST["sn"]:0;
    $fields["deleted"]	    = 0;

	if($_REQUEST["rid"] > 0)  {
        $fields["last_updated"]	= time();
        $db->update("website_basic_table", $rid, $fields);
	} else {
        $fields["created_time"]	= time();
	    $rid = $db->insert("website_basic_table", $fields);
	}
	
	$response["data"]["rid"]    = $rid;
	$response["data"]["id"]     = $rid;
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
