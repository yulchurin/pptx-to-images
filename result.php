<?php

if (isset($_GET['f'])) {
    $folder = $_GET['f'];
} else {
    header('Location: index.php');
    exit('no action');
}

// костыль...
$files = scandir($folder);
unset($files[0]);
unset($files[1]);
$i = array_search("$folder.pptx", $files);
unset($files[$i]);
$j = array_search("$folder.pdf", $files);
unset($files[$j]);

require_once 'header.php';

/*
 *
  DID NOT WORK ::::::: !!!!!!!
$gallery = preg_grep('~\.(jpeg|jpg|png)$~', scandir($folder));
$images = glob("$folder/*.{jpeg,jpg,png}", GLOB_BRACE);
var_dump($gallery);
var_dump($images);
*/

foreach ($files as $image) {
    echo "<img src=\"$folder/$image\" class=\"img-fluid\">";
}

require_once 'footer.php';