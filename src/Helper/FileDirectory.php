<?php


namespace App\Helper;


use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileDirectory
{
    /**
     * @var Filesystem
     */
    private $fsObject;

    public function __construct()
    {
        $this->fsObject = new Filesystem();
    }

    public function createDir(string $chemin, string $directory)
    {
        try {
            $new_dir_path = $chemin . "/" . $directory;
            if (!$this->fsObject->exists($new_dir_path)) {
                $old = umask(0);
                $this->fsObject->mkdir($new_dir_path, 0775);
                $this->fsObject->chown($new_dir_path, "www-data");
                $this->fsObject->chgrp($new_dir_path, "www-data");
                umask($old);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
    }

    public function removeFile(string $chemin, string $file)
    {
        try {
            $fullpath = $chemin . "/" . $file;
            if ($this->fsObject->exists($fullpath)) {
                $this->fsObject->remove($fullpath);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
    }

    public function moveFile(string $cheminSource, string $fileSource,string $cheminDestination, string $fileDestination)
    {
        try {
            $fullpathSource = $cheminSource . "/" . $fileSource;
            $fullpathDestination = $cheminDestination . "/" . $fileDestination;

            if($this->fsObject->exists($fullpathSource)) {
                dump('remove '. $cheminDestination . $fileDestination );
                $this->removeFile($cheminDestination, $fileDestination);
                dump('copy  '. $fullpathSource . $fullpathDestination );
                $this->fsObject->copy($fullpathSource, $fullpathDestination);
            } else {
                dump('file not exist '. $fullpathSource);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
    }
}