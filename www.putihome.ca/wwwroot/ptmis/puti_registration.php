<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="5,20";
include_once("website_admin_auth.php");
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

$reg_city = $db->getVal("puti_sites", "city", $admin_user["site"]);
$reg_state = $db->getVal("puti_sites", "state", $admin_user["site"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="copyright" content="Copyright Bodhi Meditation, All Rights Reserved." />
		<meta name="description" content="Bodhi Meditation Vancouver Site" />
		<meta name="keywords" content="Bodhi Meditation Vancouver" />
		<meta name="rating" content="general" />
		<meta name="language" content="english" />
		<meta name="robots" content="index" />
		<meta name="robots" content="follow" />
		<meta name="revisit-after" content="1 days" />
		<meta name="classification" content="" />
		<link rel="icon" type="image/gif" href="bodhi.gif" />
		<title>Bodhi Meditation Student Registration - Full Form</title>

		<?php include("admin_head_link.php"); ?>
		
        <script language="javascript" type="text/javascript">
		var htmlObj = new LWH.cHTML();
		$(function(){
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			"Student Registration Successful",
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			400,
				minHH:			250,
				btnMax:			false,
				resizable:		false,
				movable:			false,
				maskable: 		true,
				maskClick:		true,
				pin:				false
			});
		});
		
		function full_ajax() {
		}
    </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <center><span class="form-header"><?php echo $words["register form"]?></span></center>
    <fieldset style="border:1px solid #cccccc;">
    	<legend style="border:1px solid #cccccc;background-color:orange;"><?php echo $words["event - sign in"];?></legend>
    	<span style="font-size:14px; font-weight:bold; margin-left:2px;"><?php echo $words["select event"]?>: </span>
          <select id="event_id" style="min-width:250px;vertical-align:middle;">
          <?php 
              $query = "SELECT distinct a.id, a.title, a.start_date, a.end_date, c.title as site_desc   
			  				  FROM event_calendar a 
							  INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
                              INNER JOIN puti_sites c ON (a.site = c.id) 
                              WHERE a.deleted <> 1 AND a.status = 2 AND
                                    b.deleted <> 1 AND b.status = 1 AND
									a.site IN " . $admin_user["sites"] . " AND
									a.branch IN " . $admin_user["branchs"] . " 
                              ORDER BY event_date";
              $first = true;
			  $result = $db->query($query);
              echo '<option value=""></option>';
              while( $row = $db->fetch($result) ) {
                  $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                  if($first) {
					  $first = false;
					  echo '<option value="' . $row["id"] . '" selected>'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . $row["title"] . " [" . $date_str . ']</option>';
				  } else { 
					  echo '<option value="' . $row["id"] . '">'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . $row["title"] . " [" . $date_str . ']</option>';
				  }
              }
              
          ?>
          </select>
          <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><?php echo $words["group"]?>: <input type="text" style="width:30px; font-size:14px; font-weight:bold; text-align:center;" id="group_no" name="group_no" value="" /></span>
          <!-- <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="onsite" name="onsite" value="1" /><label for="onsite"><?php echo $words["onsite registration"]?></label></span> -->
          <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="trial" name="trial" value="1" /><label for="trial"><?php echo $words["trial"]?></label></span>
    </fieldset>

<?php 
include("tpl_fullform.php");
?>
<!--
    <form name="frm_student">
    <table border="0" width="100%">
    	<tr>
        	<td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="4"><b><?php echo $words["personal information"]?>:</b></td>
                    </tr>
                    
                	<?php if( $admin_user["lang"] != "en" ) { ?>
                	<tr>
                    	 <td class="title"><?php echo $words["last name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:50px;" id="last_name" name="last_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="first_name" name="first_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["dharma name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:50px;" id="dharma_name" name="dharma_name" value="" />
                                <input class="form-input" style="width:100px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                         </td>
                    	 <td class="title"><?php echo $words["alias"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="alias" name="alias" value="" />
                         </td>
                    </tr>
                    <tr>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal last"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:50px;" id="legal_last" name="legal_last" value="" />
                         </td>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal first"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="legal_first" name="legal_first" value="" />
                         </td>
                    </tr>
                	<?php } else {?>
                	<tr>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="first_name" name="first_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo $words["last name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="last_name" name="last_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["dharma name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:50px;" id="dharma_name" name="dharma_name" value="" />
                                <input class="form-input" style="width:100px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                         </td>
                    	 <td class="title"><?php echo $words["alias"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="alias" name="alias" value="" />
                         </td>
                    </tr>
					<?php } ?>

                    <tr>
                         <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["birth date"]?>: </td>
                         <td style="white-space:nowrap;">
                                <input class="form-input" style="width:40px; text-align:center;" id="birth_yy" name="birth_yy" maxlength="4" value="" />
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="birth_mm" name="birth_mm">
                                    <option value="0"><?php echo $words["month"]?></option>
                                    <?php
                                        for($i=1;$i<=12;$i++) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="birth_dd" name="birth_dd">
                                    <option value="0"><?php echo $words["bday"]?></option>
                                    <?php
                                        for($i=1;$i<=31;$i++) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                                
                          </td>
                    	 <td class="title"><?php echo $words["identify number"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="identify_no" name="identify_no" value="" />
                         </td>
                    </tr>

                    <tr>
                    	 <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["age range"]?>: </td>
                    	 <td style="white-space:nowrap;">
                            <select id="age_range" style="text-align:center;" name="age_range">
                                <option value="0"></option>
                                <?php
                                    $result_age = $db->query("SELECT * FROM puti_members_age order by id");
                                    while( $row_age = $db->fetch($result_age) ) {
                                        echo '<option value="' . $row_age["id"] . '">' . $row_age["title"] . '</option>';
                                    }
                                ?>
                            </select> <?php echo $words["years old"]?>
						  </td>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["id card"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="idd" name="idd" value="" />
                         </td>
                    </tr>

                	<tr>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["gender"]?>: </td>
                    	 <td  style="white-space:nowrap;">
                           	<?php
								$gender_array = array();
								$gender_array[0]["id"] 		= "Male";
								$gender_array[0]["title"] 	= "Male";
								$gender_array[1]["id"] 		= "Female";
								$gender_array[1]["title"] 	= "Female";
								echo cHTML::radio("gender", $gender_array);
							?>
	                       	<span class="required">*</span>
						  </td>
                          <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["member enter date"]?>: </td>
                          <td style="white-space:nowrap;">
                                <input class="form-input" style="width:40px; text-align:center;" id="member_yy" name="member_yy" maxlength="4" value="<?php echo date("Y")?>" />
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="member_mm" name="member_mm">
                                    <option value="0"><?php echo $words["month"]?></option>
                                    <?php
                                        for($i=1;$i<=12;$i++) {
                                            echo '<option value="' . $i . '" ' . ($i==date("n")?'selected':'') .'>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="member_dd" name="member_dd">
                                    <option value="0"><?php echo $words["bday"]?></option>
                                    <?php
                                        for($i=1;$i<=31;$i++) {
                                            echo '<option value="' . $i . '"' . ($i==date("j")?'selected':'') .'>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                          </td>
                    </tr>

					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["language ability"]?>:</b></td>
                    </tr>
					<tr>
 	                  	<td class="title"><?php echo $words["preferred language"]?>: </td>
                    	<td colspan="3" align="left">
                         	<?php 
								$result_lang = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 order by sn DESC");
								$rows_lang = $db->rows($result_lang);
								echo 
								cHTML::radio('member_lang',$rows_lang);
							?>
                        </td>
                    </tr>
					<tr>
 	                  	<td class="title"><?php echo $words["language ability"]?>: </td>
                    	<td colspan="3" align="left">
                         	<?php 
								$result_lang = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 order by sn DESC");
								$rows_lang = $db->rows($result_lang);
								echo 
								cHTML::checkbox('language',$rows_lang, 6);
							?>
                        </td>
                    </tr>


					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["contact information"]?>:</b></td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["email"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="email" name="email" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["phone"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="phone" name="phone" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["cell"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="cell" name="cell" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td colspan="4" align="left">
                         	<table>
                            	<tr>
                                	<td>
                         				<?php echo $words["preferred method of contact"]?>: 
                            		</td>
                                    <td>
										<?php
                                            $contact_array = array();
                                            $contact_array[0]["id"] 	= "Phone";
                                            $contact_array[0]["title"] 	= "Phone";
                                            $contact_array[1]["id"] 	= "Email";
                                            $contact_array[1]["title"] 	= "Email";
                                            echo cHTML::checkbox("contact_method", $contact_array);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                         </td>
                    </tr>
					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["address information"]?>:</b></td>
                    </tr>
                    <tr>
                    	 <td class="title"><?php echo $words["address"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="address" name="address" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["city"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="city" name="city" value="<?php echo $reg_city;?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["state"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="state" name="state" value="<?php echo $reg_state;?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["postal code"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="postal" name="postal" value="" />
                         </td>
                    </tr>
                </table>
            </td>
            <td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="2"><b><?php echo $words["emergency contact name and relationship"]?>:</b></td>
                    </tr>
					<tr>
                    	<td class="title"><?php echo $words["contact name"]?>: </td>
                        <td>
                        	<input class="form-input" id="emergency_name" name="emergency_name" value="" />
                        </td>
                    </tr>
					<tr>
                    	<td class="title"><?php echo $words["contact phone"]?>: </td>
                        <td>
                        	<input class="form-input" id="emergency_phone" name="emergency_phone" value="" />
                        </td>
                    </tr>
					<tr>
                    	<td class="title"><?php echo $words["relationship"]?>: </td>
                        <td>
                        	<input class="form-input" id="emergency_ship" name="emergency_ship" value="" />
                        </td>
                    </tr>
					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["how did you hear about us?"]?></b></td>
                    </tr>
					<tr>
                    	<td colspan="2" align="left">
                         	<?php 
								$result_hearfrom = $db->query("SELECT * FROM puti_info_hearfrom Order BY id");
								$rows_hearfrom = $db->rows($result_hearfrom);
								echo 
								$admin_user["lang"]=="en"?
								cHTML::checkbox('hear_about',$rows_hearfrom,6):
								cHTML::checkbox('hear_about',$rows_hearfrom,8);
							?>
                        </td>
                    </tr>
					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["ailment & symptom"]?></b></td>
                    </tr>
					<tr>
                    	<td colspan="2" align="left">
                         	<?php 
								$result_symptom = $db->query("SELECT * FROM puti_info_symptom Order BY id");
								$rows_symptom = $db->rows($result_symptom);
								echo ($admin_user["lang"]=="en"?cHTML::checkbox('symptom',$rows_symptom,4):cHTML::checkbox('symptom',$rows_symptom,6));
							?><br />
                            <span><?php echo $words["specify"]?>: <input type="text" id="other_symptom" name="other_sympton" style="width:200px;" value="" /></span>
                        </td>
                    </tr>
					<tr>
                    	<td colspan="2" class="line">
	                      	<b><?php echo $words["are you currently receiving therapy of some kind?"]?></b>
                           	<?php
								$therapy_array = array();
								$therapy_array[0]["id"] 	= "0";
								$therapy_array[0]["title"] 	= "No";
								$therapy_array[1]["id"] 	= "1";
								$therapy_array[1]["title"] 	= "Yes";
								echo cHTML::radio("therapy", $therapy_array);
							?>
                        	<div id="div_therapy_yes" style="display:none;">
	                      	<?php echo $words["if yes, please provide details regarding the nature of the therapy/treatment"]?> : 
                            <textarea id="therapy_content" name="therapy_content" style="width:98%; height:40px; resize:none;"></textarea>
							</div>
                        </td>
                    </tr>

					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["transportation"]?> : </b></td>
                    </tr>
					<tr>
                    	<td colspan="2" align="left">
                         	<?php 
								$result_carpool = $db->query("SELECT * FROM puti_info_carpool Order BY id");
								$rows_carpool = $db->rows($result_carpool);
								echo cHTML::radio('transportation',$rows_carpool,0,10);
							?><br />
                            <?php echo $words["if driving, please help"]?> : 
                            <span id="span_carpool">{
                            <span>
								<?php echo $words["plate no"]?>: <input type="text" id="plate_no" name="plate_no" style="width:80px;" value="" />
                            </span>
                            <span style="margin-left:5px;">
                            	<input type="checkbox" id="offer_carpool" name="offer_carpool" value="1" /><label for="offer_carpool"><?php echo $words["offer carpool"]?></label>
                            </span>}
                            </span>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
  
        <tr>
            <td colspan="2" class="line"><b><?php echo $words["please write down any other medical concerns or history"]?> : </b></td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <textarea id="medical_concern" name="medical_concern" style="width:98%; height:60px; resize:none;"></textarea>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="line"><b><?php echo $words["email subscription"]?> <span class="required">***</span>: </b></td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <span style="font-size:14px;">
                    <?php echo cTYPE::gstr($words["email subscription agreement"])?> 
                </span><br />
                <center>
                	<input type="radio" id="irefuse" 	name="email_flag" value="0" /><label for="irefuse"><b><?php echo $words["i dont agree"]?></b></label>
                    <input type="radio" id="iagree" 	name="email_flag" value="1" style="margin-left:50px;" /><label for="iagree"><b><?php echo $words["i agree"]?></b></label>
              	</center>
            </td>
        </tr>
      
        <tr>
        	<td class="line" colspan="2" align="center" style="padding-top:5px; padding-bottom:20px;">
            	<input type="button" right="save" id="btn_submit" name="btn_submit" value="<?php echo $words["submit"]?>" style="font-size:14px; font-weight:bold;"  />
            </td>
        </tr>
    </table>
    </form>
-->
<?php 
include("admin_footer_html.php");
?>
<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

</body>
</html>