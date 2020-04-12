#!/usr/bin/env php
<?php
if(!$argv[1]) die("No input file!");
if(!file_exists($argv[1])) die("Input file not found!");
$error = false;
$dom = simplexml_load_file($argv[1]);
// add in required name, code, version, author and link tags
$insert = '<modification>';
if(!count($dom->name)) {
    $insert .= "\n  <name>". ($dom->id?$dom->id:basename($argv[1], '.xml'))."</name>";
}
if(!count($dom->code)) {
    $insert .= "\n  <code>". ($dom->id?$dom->id:basename($argv[1], '.xml'))."</code>";
}
if(!count($dom->version)) {
    $insert .= "\n  <version>0.0</version>";
}
if($insert != '<modification>') {
    $dom = simplexml_load_string(str_replace('<modification>',$insert, $dom->asXML()));
}
// change name to path
foreach($dom->file as $key => $node) {
    switch(true) {
        case $node['name'] && $node['path']:
            $error = true;
            echo "Both path and name set for file node!\n";
            break;
        case $node['name']:
            $node->addAttribute('path', $node['name']);
            break;
    }
    unset($node['error']);
    unset($node['name']);
    if(!isset($node['path'])) {
        $error = true;
        echo "No path set for file node!\n";
    }
}
// move attributes from search to add
foreach($dom->xpath('//search') as $key => $node) {
    if(isset($node['position']) && !isset($dom->xpath('//add')[$key]['position'])) {
        $dom->xpath('//add')[$key]->addAttribute('position', $node['position']);
    }
    if(isset($node['trim']) && !isset($dom->xpath('//add')[$key]['trim'])) {
        $dom->xpath('//add')[$key]->addAttribute('trim', $node['trim']);
    }
    if(isset($node['offset']) && !isset($dom->xpath('//add')[$key]['offset'])) {
        $dom->xpath('//add')[$key]->addAttribute('offset', $node['offset']);
    }
    if(isset($node['index']) && strtoupper($node['index']) != "FALSE") {
		  // Must reduce index by 1
		  $index = explode(',',$node['index']);
		  foreach($index as &$val) {
		  	   $val--;
		  }
		  $node['index'] = implode(',', $index);
    }
    unset($node['position']);
    unset($node['trim']);
    unset($node['offset']);
    unset($node['error']);
}
// validate attributes
$allowed = array('replace', 'before', 'after');
foreach($dom->xpath('//add') as $key => $node) {
    if(isset($node['position']) && !in_array($node['position'], $allowed)) {
        $error = true;
        echo $node['position']." is not a valid position!\n";
    }
}
foreach($dom->xpath('//operation') as $key => $node) {
	if(!isset($node['error']) || $node['error'] != 'abort') {
		// default to skip
		$node['error'] = 'skip';
	}
}
if($error) {
    echo "Errors found, abort.\n";
} elseif(isset($argv[2])) {
    $dom->asXML($argv[2]);
} else {
    echo $dom->asXML();
}