<?php
/*
 JSON_SQL_Bridge 1.0
 Copyright 2016 Frank Vanden berghen
 All Right reserved.

 JSON_SQL_Bridge is not a free software. The JSON_SQL_Bridge software is NOT licensed under the "Apache License".
 If you are interested in distributing, reselling, modifying, contibuting or in general creating
 any derivative work from JSON_SQL_Bridge, please contact Frank Vanden Berghen at frank@timi.eu.
 */
namespace kibella; require_once __DIR__."/../config.php"; class l0 { private $O0=NULL; public function l1($O1) { $O1=addslashes(htmlentities($O1,ENT_QUOTES)); $this->O0 =dbcreatedbconnection(KIBELLADB); $l2="SELECT ".O2."\n            FROM ".l3."\n            WHERE ".O3." = \"".$O1."\" AND ".l4." = \"".O4."\""; $l5=dbdbhexecutesqlquery($this->O0->getdbhandle(),$l2,$O5="query"); if (count($l5)>1) { showmessage("dashboard.php","warning","While checking the shared property for dashboard with ID '$O1',\n      \t\tit was found that there are more than one record associated to the dashboard.\n      \t\tThis can cause unexpected results.\nPlease contact technical support to solve this issue.\n"); } return $l5[0][O2]; } public function l6($O1,$O6) { $l7=new user(); if ($l7->isloggedin() && is_bool($O6)) { $O1=addslashes(htmlentities($O1,ENT_QUOTES)); if ($O6) $O6=1; else $O6=0; $this->O0 =dbcreatedbconnection(KIBELLADB); $l2="UPDATE ".l3."\n              SET ".O2." = ".$O6."\n              WHERE ".O3." = \"".$O1."\" AND ".l4." = \"".O4."\""; $l5=dbdbhexecutesqlquery($this->O0->getdbhandle(),$l2,$O5="exec"); return $l5; } return FALSE; } }