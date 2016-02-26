<?php

use AnymapGeoJson\MunicipalBrazilianGeodata;
use Composer\Autoload\ClassLoader;

define('PATH', realpath(__DIR__));

/* @var $autoload ClassLoader */
$autoload = require PATH . '/vendor/autoload.php';

$mbg = new MunicipalBrazilianGeodata();

$di = new DirectoryIterator(PATH . '/municipal-brazilian-geodata/data');

foreach ($di as $directory) {
    /* @var $directory DirectoryIterator */

    if (!preg_match('/^([A-Z]{2})\.json$/', $directory->getFilename(), $matches)) {
        continue;
    }
    
    $uf = $matches[1];
    echo "Processando $uf\n";
    
    $ufObject = json_decode(file_get_contents($directory->getPathname()));

    $mbg->processUfJson($ufObject);
}