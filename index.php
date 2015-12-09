<?php

ini_set('memory_limit', '2048M');

include 'XtExplorer.php';

// scan xdebug trace output dir for files
$traceFolder = ini_get('xdebug.trace_output_dir');
$files  = scandir($traceFolder);
$traceFiles = [];
foreach ($files as $f) {
    if ($f != '.' && $f != '..') $traceFiles[] = $f;
}

// also accept custom path
$traceFile = isset($_GET['filePath']) ? $_GET['filePath'] : '';

if (empty($traceFile) && isset($_GET['action']) && $_GET['action'] == 'go_to_last' && count($traceFiles) >= 1)
{
    $traceFile = $traceFolder . DIRECTORY_SEPARATOR . $traceFiles[count($traceFiles)-1];
}

if ($traceFile != '') {
    $traceExplorer = new XtExplorer($traceFile);
    //$traceExplorer->filterPrefix = '/var/www/tala';
}

include 'view.php';