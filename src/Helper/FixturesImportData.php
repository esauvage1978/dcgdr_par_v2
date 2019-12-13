<?php

namespace App\Helper;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FixturesImportData
{
    /**
     * @var string
     */
    private $directory_import;

    /**
     * @var string
     */
    private $fileName;
    public function __construct(ParameterBagInterface $params)
    {
        $this->directory_import=$params->get('directory_import');
    }

    private function  setFileSource(string $fileName)
    {
        $this->fileName=$fileName;
    }

    public function importToArray(string $fileName): Array
    {
        $this->setFileSource($fileName);

        if (!$this->checkFile()) {
            throw new \InvalidArgumentException('Le fichier '. $this->fileName .' n\'existe pas dans le répertoire ' . $this->directory_import);
        }

        $data= json_decode(
            $this->readJson(),
            true
        );

        if($data===null) {
            throw new \InvalidArgumentException('Le fichier '. $this->fileName .' est vide ou n\'est pas un json valide');
        }

        return $data;
    }

    private function checkFile()
    {
        return  is_file($this->getPath());
    }

    private function getPath() {
        return $this->directory_import .
            $this->fileName;
    }

    private function readJson(): string
    {
        return file_get_contents(
            $this->getPath()
        );
    }
}