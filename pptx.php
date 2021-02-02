<?php

/*
 * Для корректной работы на сервере должны быть установлены:
 * LibreOffice
 * poppler-utils
 * GhostScript
 * Должны быть установлены соответствующие права в sudoers
*/

namespace Presentation;

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
        $string = str_replace(' ', '_', $pptx['name']);
        $this->name = htmlspecialchars($string);
        $this->tmp_name = $pptx['tmp_name'];
        $this->dir = substr($string, 0 , (strrpos($string, '.')));
    }

    public function upload()
    {
        $max_size = 100000000;

        if ($this->size > $max_size) {
            return("размер файла не должен превышать $max_size Байт");
        } elseif ($this->type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                  /* && TODO: включить проверку первых байтов по шаблону: 50 4B 03 04 14 00 06 00
                  *    либо сделать unzip и посмотреть по структуре, чтобы понять, что там нормальный PPTX,
                  *    а не фашист троян. Решение будет чуть позже
                  *    проверка просто по $_FILE['type'] не даёт никаких гарантий
                  */
                  
                 ) {
            mkdir($this->dir);
            move_uploaded_file($this->tmp_name, $this->dir.'/'.$this->name);
            $cmd = "sudo libreoffice --headless --convert-to pdf --outdir $this->dir $this->dir/$this->name";
            $this->pdffile = $this->dir.'/'.$this->dir.'.pdf';
            if (shell_exec($cmd)) {
                return 'presentation was uploaded and was converted into pdf';
            } else {
                return 'something wrong';
            }
        } else {
            return 'nope';
        }
    }

    public function convertToJPG()
    {
        $this->pdftoppmConverse();
    }


    /**
     * @return false|string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return mixed
     */
    public function getPdffile()
    {
        return $this->pdffile;
    }

    private function pdftoppmConverse()
    {
        $cmd = "sudo pdftoppm -jpeg -r 96 $this->pdffile $this->dir/slide";
        if (shell_exec($cmd)) {
            return('success');
        } else {
            return('pdf to image conversion error');
        }
    }

}
