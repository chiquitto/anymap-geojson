<?php

namespace AnymapGeoJson;

use stdClass;

class MunicipalBrazilianGeodata
{

    private $outputDirectory;

    public function __construct()
    {
        $this->checkOutputDirectory();
    }

    public function checkOutputDirectory()
    {
        $destino = PATH . '/json';

        if (!is_dir($destino)) {
            mkdir($destino);
        }
        $this->outputDirectory = $destino;
    }

    private function joinMunicipio($municipio1, $municipio2)
    {
        foreach ($municipio2->geometry->coordinates as $coordinates) {
            $municipio1->geometry->coordinates[] = $coordinates;
        }
    }

    public function processBrasilJson(stdClass $brasilObject)
    {
        $ufs = [];

        $ids = [
            'AC' => 12,
            'AL' => 27,
            'AM' => 13,
            'AP' => 16,
            'BA' => 29,
            'CE' => 23,
            'DF' => 53,
            'ES' => 32,
            'GO' => 52,
            'MA' => 21,
            'MG' => 31,
            'MS' => 50,
            'MT' => 51,
            'PA' => 15,
            'PB' => 25,
            'PE' => 26,
            'PI' => 22,
            'PR' => 41,
            'RJ' => 33,
            'RN' => 24,
            'RO' => 11,
            'RR' => 14,
            'RS' => 43,
            'SC' => 42,
            'SE' => 28,
            'SP' => 35,
            'TO' => 17,
        ];

        foreach ($brasilObject->features as & $uf) {
            $uf->properties = (object) [
                        'id' => $ids[$uf->properties->UF],
                        'name' => $uf->properties->ESTADO,
                        'sigla' => $uf->properties->UF,
            ];
        }
        
        $json = json_encode($brasilObject);

        file_put_contents("{$this->outputDirectory}/bra.json", $json);
    }

    public function processUfJson(stdClass $ufObject)
    {
        $municipios = [];

        foreach ($ufObject->features as $municipio) {
            $ufMunicipio = $municipio->properties->UF;
            $idMunicipio = (int) $municipio->properties->GEOCODIGO;
            $municipio->properties = (object) [
                        'id' => $idMunicipio,
                        'name' => $municipio->properties->NOME,
                    // 'uf' => $municipio->properties->UF
            ];

            switch ($idMunicipio) {
                case 2605459:
                    $this->process2605459($municipio);
                    break;
                case 3205309:
                    $this->process3205309($municipio);
                    break;
            }

            if (!isset($municipios[$idMunicipio])) {
                $municipios[$idMunicipio] = $municipio;
            } else {
                $this->joinMunicipio($municipios[$idMunicipio], $municipio);
            }
        }

        $ufObject->features = array_values($municipios);

        $json = json_encode($ufObject);

        file_put_contents("{$this->outputDirectory}/{$ufMunicipio}.json", $json);
    }

    /**
     * Fix for Fernando de Noronha city
     */
    private function process2605459(stdClass $municipio)
    {
        for ($i = 0; $i < count($municipio->geometry->coordinates[0]); $i++) {
            $municipio->geometry->coordinates[0][$i][0] -= 3.5;
            $municipio->geometry->coordinates[0][$i][1] -= 3.5;
        }
    }

    /**
     * Fix for islands of Vitoria city
     */
    private function process3205309(stdClass $municipio)
    {
        // Remove islands
        $coordinates = [];

        foreach ($municipio->geometry->coordinates as $coordinate) {
            if (($coordinate[0][0] == -29.34624) && ($coordinate[0][1] == -20.5031)) {
                continue;
            } elseif (($coordinate[0][0] == -28.8434) && ($coordinate[0][1] == -20.47467)) {
                continue;
            } elseif (($coordinate[0][0] == -28.84411) && ($coordinate[0][1] == -20.45822)) {
                continue;
            } elseif (($coordinate[0][0] == -28.83889) && ($coordinate[0][1] == -20.46453)) {
                continue;
            }

            $coordinates[] = $coordinate;
        }

        $municipio->geometry->coordinates = $coordinates;
    }

}
