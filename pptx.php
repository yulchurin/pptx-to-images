<?php

/*
 * Для корректной работы на сервере должны быть установлены:
 * LibreOffice
 * Расширение PHP ImageMagick
 * GhostScript
 * Должны быть установлены соответствующие права в sudoers
 * sudo libreoffice --headless --convert-to pdf --outdir aaa proper.pptx
*/

namespace Presentation;

use Imagick;

class Pptx
{
    private $name;
    private $tmp_name;
    private $type;
    private $size;
    private $dir;
    private $pdffile;

    public function __construct($pptx)
    {
        $this->size = $pptx['size'];
        $this->type = $pptx['type'];
        $this->name = htmlspecialchars($pptx['name']);
        $this->tmp_name = $pptx['tmp_name'];
        $this->dir = substr($pptx['name'], 0 , (strrpos($pptx['name'], '.')));
    }

    public function upload()
    {
        $max_size = 100000000;

        if ($this->size > $max_size) {
            return("размер файла не должен превышать $max_size Байт");
        } elseif ($this->type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
            mkdir($this->dir);
            move_uploaded_file($this->tmp_name, $this->dir.'/'.$this->name);
            $cmd = "sudo libreoffice --headless --convert-to pdf --outdir $this->dir $this->dir/$this->name";
            $this->pdffile = $this->dir.'/'.$this->dir.'.pdf';
            if (shell_exec($cmd)) {
                return 'pptx was uploaded and was converted into pdf';
            } else {
                return 'something wrong';
            }
        } else {
            return 'nope';
        }
    }

    public function convertToPNG()
    {
        $this->imagickConverse('PNG');
    }

    public function convertToJPG()
    {
        $this->imagickConverse('JPG');
    }

    private function imagickConverse($ext)
    {
        $im = new imagick($this->pdffile);
        $pages = $im->getNumberImages();

        if ($pages < 3) {
            $resolution = 300;
        } else {
            $resolution = floor(sqrt(500000/$pages));
        }
        $imagick = new imagick();
        $imagick->setResolution($resolution, $resolution);
        $imagick->readImage($this->pdffile);
        $imagick->setImageFormat($ext);
        foreach($imagick as $i => $imagick) {
            $imagick->writeImage($this->dir . "/page-". ($i+1) .'.'.$ext);
        }
        $imagick->clear();
    }

    /**
     * @return false|string
     */
    public function getDir()
    {
        return $this->dir;
    }

}