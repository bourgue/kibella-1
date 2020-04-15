<?php
/*
JSON_SQL_Bridge 1.0
Copyright 2016 Frank Vanden berghen
All Right reserved.

JSON_SQL_Bridge is not a free software. The JSON_SQL_Bridge software is NOT licensed under the "Apache License". 
If you are interested in distributing, reselling, modifying, contibuting or in general creating 
any derivative work from JSON_SQL_Bridge, please contact Frank Vanden Berghen at frank@timi.eu.
*/
namespace kibella; const DEBUG=FALSE; const LOG=FALSE;
define(__NAMESPACE__."\\APPDIRNAME",basename(dirname( __DIR__))); 
define(__NAMESPACE__."\\ESDIRNAME",APPDIRNAME."/db");
define(__NAMESPACE__."\\TEMPDIR",__DIR__."/../tempdata");
define(__NAMESPACE__."\\TABLESDIR",TEMPDIR); const O7=".kibana-4"; const l8="4.1.2";
define(__NAMESPACE__."\\LOGFILE",TEMPDIR."/kibella.log"); define(__NAMESPACE__."\\CACHEDIR",TEMPDIR."/cache"); const CACHEMODE_DAY="day";
const O8=03410; const l9="LastResponse"; const O9="TimeOfLastQuery"; const la="CacheEnabledLastQuery"; const Oa=1; const lb="NOTE";
const Ob="WARNING"; const lc="ERROR"; const Oc="INTERNAL ERROR"; const ld="Contact your software provider"; const Od=-1; const le=-2;
const Oe=-3; const lf=0; const Of=1; const lg="config"; const Og="dashboard"; const lh="index-pattern"; const Oh="search"; const Oi="visualization";
const lj="_index"; const Oj="_type"; const lk="_id"; const Ok="_version"; const ll="_score"; const lm="_source"; const Om="_shared"; const ln="_shards";
const On="created"; const lo="found"; const Oo=1; const lp=7; const KIBELLADB="kibella.sqlite"; const Op="dbtemp"; const lq=lk; const Oq=Ok;
const lr="RegisteredTables"; const ls="tablename"; const Os="db"; const lt="dbtype"; const Ot="datefields"; const lu="geofields"; const Ou="linkfields";
const lv="enablecache"; const Ov="Objects"; const lw="tableid"; const Ow=Oj; const lx=lm; const Ox="query"; const ly="_shared"; const Oy="Queries";
const lz="md5"; const Oz="lastdate"; const l10="tableid"; const O10="query"; const l11="counter"; const O11=0; const l12="DefaultIndex"; const O12="query";
const l13="no_database.sqlite"; const O13="sqlite"; const l14="no_table"; const O14="-"; const l15=" and "; const O15="desc"; const l16="avg";
const O16="cardinality"; const l17="value_count"; const MAX="max"; const MIN="min"; const O17="percentiles"; const l18="std_dev"; const O18="sum";
const l19="aggregations"; const O19="aggs"; const l1a="AGGS_FIELD"; const O1a="AGGS_RANGE"; const l1b="date_range"; const O1b="bool"; const l1c="buckets";
const O1c="filter"; const l1d="geo_bounding_box"; const O1d="bottom_right"; const l1e="FILTER_GEOHASH_FIELD"; const O1e="top_left"; const l1f="match";
const O1f="FILTER_MATCH_FIELD"; const l1g="FILTER_RANGE"; const O1g="FILTER_RANGE_FIELD"; const l1h="filtered"; const O1h="geohash_grid"; const l1i="highlight";
const O1i="histogram"; const l1j="date_histogram"; const O1j="must"; const l1k="must_not"; const O1k="operator"; const l1l="order"; const O1l="query";
const l1m="query_string"; const O1m="range"; const l1n="ranges"; const O1n="ROOT"; const l1o="term"; const O1o="terms"; const l1p="STATISTIC";
const O1p="analyze_wildcard"; const l1q="_count"; const O1q="default_field"; const l1r="default_operator"; const O1r="field"; const l1s="fields";
const O1s="format"; const l1t="from"; const O1t="lat"; const l1u="lon"; const O1u="gt"; const l1v="gte"; const O1v="interval"; const l1w="keyed";
const O1w="lt"; const l1x="lte"; const O1x="min_doc_count"; const l1y="operator"; const O1y="precision"; const l1z="query"; const O1z="script_fields";
const l20="size"; const O20="to"; const l21="type";

const OBJ_COLUMN_SHARED="_shared";
const OBJ_COLUMN_SHOW_RAW_TABLES="_showrawtables";
const ALL_COLUMN_ID="_id";
const OBJ_COLUMN_TYPE="_type";
const OBJTABLE="Objects";
const NAME_DASHBOARD="dashboard";