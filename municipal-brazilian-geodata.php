<?php

use AnymapGeoJson\MunicipalBrazilianGeodata;
use Composer\Autoload\ClassLoader;

define('PATH', realpath(__DIR__));
define('PATH_DATA', PATH . '/municipal-brazilian-geodata/data');

/* @var $autoload ClassLoader */
$autoload = require PATH . '/vendor/autoload.php';

$mbg = new MunicipalBrazilianGeodata();

// Brasil
echo "Processando Brasil.json\n";
$brasilObject = json_decode(file_get_contents(PATH_DATA . '/Brasil.json'));
$mbg->processBrasilJson($brasilObject);

// Uf
$di = new DirectoryIterator(PATH_DATA);
foreach ($di as $directory) {
    /* @var $directory DirectoryIterator */

    if (!preg_match('/^([A-Z]{2})\.json$/', $directory->getFilename(), $matches)) {
        continue;
    }
    
    $uf = $matches[1];
    echo "Processando {$directory->getFilename()}\n";
    
    $ufObject = json_decode(file_get_contents($directory->getPathname()));

    $mbg->processUfJson($ufObject);
}