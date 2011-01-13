<?php

//Change the current work directory
 chdir($_GET['cwd']);


include_once 'includes/bootstrap.inc';
include_once 'includes/common.inc';
include_once 'includes/module.inc';
include_once 'includes/unicode.inc';
//Check the existence or not of the mb_string extension
unicode_check();


drupal_bootstrap(DRUPAL_BOOTSTRAP_LANGUAGE);


//Loading required modules

drupal_load('module', 'system');

drupal_load('module', 'whami_source_adapter');

drupal_load('module', 'geonames');

drupal_load('module', 'whami_source_adapter_wikipedia');

//Loading module list 
module_list(TRUE, FALSE);

//Call to the sourceAdapter
if($_GET['method'] == 'bbox'){
  $bbox = array(
    'north' => $_GET['north'],
    'south' => $_GET['south'],
    'east' => $_GET['east'],
    'west' => $_GET['west'],
  );
  $result = whami_get_information_bounding_box($bbox, $_GET['language'], $_GET['categoriesID'], $_GET['sourceID'], $_GET['maxRows'], $_GET['titleshrink'], $_GET['bodyshrink'], $_GET['linkshrink'], $_GET['maxRequestRows'], $_GET['sessionID']);
}else if($_GET['method'] == 'geopoint_radius'){
  $result = whami_get_information_geopoint_radius($_GET['lon'], $_GET['lat'], $_GET['radius'], $_GET['language'], $_GET['categoriesID'], $_GET['sourceID'], $_GET['maxRows'], $_GET['titleshrink'], $_GET['bodyshrink'], $_GET['linkshrink'], $_GET['maxRequestRows'], $_GET['sessionID']);
}

if (isset($result)){
  foreach($result as $key => $value){
    drupal_write_record('whami_temporary_table', $result[$key]);
  } 
}
exit();