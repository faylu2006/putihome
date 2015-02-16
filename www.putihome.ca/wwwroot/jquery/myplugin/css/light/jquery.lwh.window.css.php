/************************************************************************************/
/*  JQuery Plugin  Custom Window                                                   	*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-3-29      															*/
/*  Files: 	jquery.lwh.window.js ;  jquery.lwh.window.css							*/
/************************************************************************************/
.lwhWindow {
	cursor:		default;
	position:	absolute;
	display:	none;
	top:		-2000px;
	left:		-2000px;
	border:		1px solid #777777;
	
	padding:			25px 5px 12px 5px !important;
	background-color:	#ffffff;
	
	overflow:			hidden !important;
}

/* background color open for your special case,  what you setup is up to you */
.lwhWindow-bgColor1 {
	background-color:	#999999;
}

.lwhWindow-bgColor2 {
	background-color:	#aaaaaa;
}

/* mask iframe and div */
.lwhWindow-mask-ifrm {
	display:	none;
	position:	absolute;
	border:		1px solid #eeeeee; 
	width:		0px; 
	height:		0px; 
	left:		-2000px; 
	top:		-2000px;

	filter:				alpha(opacity:20);
	opacity:			0.2;
	background-color:	#000000;
}

.lwhWindow-mask-div {
	display:	none;
	position:	absolute;
	border:1px solid #eeeeee; 
	width:0px height:0px; 
	left:-2000px; 
	top:-2000px;

	filter:				alpha(opacity:20);
	opacity:			0.2;
	background-color:	#000000;
}


.lwhWindow-header {
	cursor:				default;
	position:			absolute;
	left:				0px;
	top:				0px;
	display:			block;
	width:				100%;
	height:				22px;
	line-height:		22px;
	border-bottom: 		1px solid #888888;
	
	background-color:	#999999;
}

.lwhWindow-header-title {
	display:		inline-block;
	color:			#eeeeee;
	
	position:		absolute;
	left:			10px;
	top:			0px;
	width:			85%;
	
	height:			22px;
	line-height:	22px;
	font-size:		14px;
	font-weight:	bold;
	
	overflow:hidden; 
	text-decoration:none;
	text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	white-space:nowrap;
}

.lwhWindow-header-close {
	cursor:			pointer;
	display: 		inline-block;
	
	position: 		absolute;
	width:			26px;
	height:			20px;
	
	left:			100%;
	top:			1px;
	margin-left:	-30px;
	vertical-align:	middle;

	background: 	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhWindow-btn-close.png" , "png");?>)  center center no-repeat;
}

/* header  maximize and minimize button */
.lwhWindow-header-maxmin {
	cursor:			pointer;
	display: 		inline-block;
	
	position: 		absolute;
	width:			26px;
	height:			20px;
	
	left:			100%;
	top:			1px;
	margin-left:	-56px;
	vertical-align:	middle;
}
.lwhWindow-header-minimum {
	background: 	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhWindow-btn-minimum.png" , "png");?>)  center center no-repeat;
}

.lwhWindow-header-maxium {
	background: 	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhWindow-btn-maxium.png" , "png");?>)  center center no-repeat;
}


.lwhWindow-content {
	position:			relative;
	background-color:	#ffffff;
	border:				1px solid #cccccc;
	padding:			5px;
	overflow:			hidden;
}

.lwhWindow-resize {
	position:			relative;
}

.lwhWindow-scroll {
	overflow: 				auto;
}

.lwhWindow-hscroll {
	overflow-x: 			auto;
	overflow-y: 			none;
}

.lwhWindow-vscroll {
	overflow-x: 			none;
	overflow-y: 			auto;
}