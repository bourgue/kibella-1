<?php
/*
JSON_SQL_Bridge 1.0
Copyright 2016 Frank Vanden berghen
All Right reserved.

JSON_SQL_Bridge is not a free software. The JSON_SQL_Bridge software is NOT licensed under the "Apache License". 
If you are interested in distributing, reselling, modifying, contibuting or in general creating 
any derivative work from JSON_SQL_Bridge, please contact Frank Vanden Berghen at frank@timi.eu.
*/
namespace kibella; if (LOG) { global $O8e; $O8e=@fopen(LOGFILE,"a+"); } global $l4d; global $O68; global $O4s; global $O4r; global $O9h; global $O5e; global $l91; global $O91; global $Obu; global $lbv; global $Obv; $l4d=array("sqlite" => l19); $O68=array_keys($l4d); $O6h=array(l18); $O4s=array(l19 => l18,O19 => O18); $O4r=array(l1a => array(l1b => "(",O1b => ")",l19 => "select * from sqlite_master where lower(type) = 'table' and lower(name) = lower"),O1a => array(l1b => "(",O1b => ")",l19 => "PRAGMA table_info")); $O9h=array(l1c => array(l19 => "rowid"),O1c => array(l19 => "IFNULL")); $O5e=array("s" => "second","m" => "minute","h" => "hour","d" => "day","w" => "week","M" => "month","y" => "year"); $O91=array(O20 => "avg",l21 => "count(distinct",MAX => "max",MIN => "min",l22 => "percentiles",O22 => "stddev",l23 => "sum",O21 => "count",); $Obu=array(O20,l21,O21,MAX,MIN,l22,l23); $l91=array(l24 => array(NULL),O24 => array(array_values($Obu),l24,l25,O25,l2c,l2d,O2d,l2j),l25 => array(NULL,O2h,l2m,O2q),O25 => array(NULL,O2h,l2m,O2q),l26 => array(l2e,O2e),l27 => array(l26,l25,l2g),O27 => array(NULL),O28 => array(l28,l29),O29 => array(NULL),l2a => array(O2s,O2t,O2v),O2a => array(NULL),l2b => array(l2n,l2p,O2p,l2r,O2r),O2b => array(l27,l2g),l2c => array(l2m,l2t),O2c => array(l2u),l2d => array(l2m,l2q,l2s),O2d => array(l2m,l2q),l2e => array(NULL,O27,l2g),O2e => array(NULL,O27,l2g),O2f => array(NULL,O2k),l2g => array(O2b,O29,O2g),O2g => array(l2k,l2l,O2l,O2m,O2t),O2h => array(O2n,l2v),l2i => array(l24,O2c,l2g,O2u),O2j => array(l2m),O2i => array(NULL),l2j => array(O2f,l2m,O2u)); $lbv=array(O1f,l1g,O1g,l1h,O1h,l1i);