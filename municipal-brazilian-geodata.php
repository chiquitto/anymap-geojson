<?php

namespace anymapGeoJson;

use DirectoryIterator;

$di = new DirectoryIterator(__DIR__ . '/municipal-brazilian-geodata/data');
$destino = __DIR__ . '/json';

if (!is_dir($destino)) {
    mkdir($destino);
}
$destino = realpath($destino);

foreach ($di as $directory) {
    /* @var $directory DirectoryIterator */
    
    if (!preg_match('/^([A-Z]{2})\.json$/', $directory->getFilename(), $matches)) {
        continue;
    }
    echo $directory->getPathname() . "\n";
    
    $uf = $matches[1];
    
    $o = json_decode(file_get_contents($directory->getPathname()), 1);
    
    foreach ($o['features'] as & $features) {
        $features['properties'] = [
            'id' => (int) $features['properties']['GEOCODIGO'],
            'name' => $features['properties']['NOME']
        ];
    }
    
    $json = json_encode($o);
    
    file_put_contents("{$destino}/{$uf}.json", $json);
}