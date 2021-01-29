<?php

require_once 'pptx.php';

use Presentation\Pptx;

if (isset($_POST['submit'])) {

    if (isset($_FILES['pptx'])) {
        $pptx = new Pptx($_FILES['pptx']);
        echo $pptx->getDir();
        echo $pptx->upload();
        if (isset($_POST['output']) && $_POST['output']==='jpg') {
            echo '<p>JPG</p>';
            $pptx->convertToJPG();
        } elseif (isset($_POST['output']) && $_POST['output']==='png') {
            echo '<p>PNG</p>';
            $pptx->convertToPNG();
        } elseif (isset($_POST['output']) && $_POST['output']==='pdf') {
            echo '<p>pdf</p>';
        } else {
            echo '<p>filetype was not selected</p>';
        }
        echo '<p><a href="result.php?f=', $pptx->getDir(), '">view result</a></p>';
    }

}