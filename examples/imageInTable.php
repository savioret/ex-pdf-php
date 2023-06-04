<?php

$ext = '../extensions/CezTableImage.php';
if (!file_exists($ext)) {
    die('This example requires the CezTableImage.php extension');
}

include $ext;
$pdf = new Cezpdf('a4');

$pdf->selectFont('Helvetica');

$image = '../ros.jpg';
// test gif file
//$image = 'images/test_alpha.gif';

$data = array(
    ['num' => 1, 'name' => 'gandalf', 'type' => '<C:image:'.$image.',90>'],
    ['num' => 4, 'name' => 'saruman', 'type' => 'baddude', 'url' => 'http://sourceforge.net/projects/pdf-php'],
    //['num' => 5, 'name' => 'sauron', 'type' => '<C:image:'.urlencode($image).',90>'],
    ['num' => 5, 'name' => 'sauron', 'type' => '<C:image:'.$image.',90>'],
    ['num' => 6, 'name' => 'sauron', 'type' => '<C:image:'.$image.'><C:image:'.$image.',90>'."\nadadd"],
    ['num' => 8, 'name' => 'sauron', 'type' => '<C:image:'.$image.',90>'],
    ['num' => 10, 'name' => 'sauron', 'type' => '<C:image:'.$image.',50>'],
    ['num' => 11, 'name' => 'sauron', 'type' => '<C:image:'.$image.'>'],
    /* ['num'=>12,'name'=>'sauron','type'=>'<C:showimage:'.urlencode('http://myserver.mytld/myimage.jpeg'].'>'), */
);


$pdf->ezTable($data, '', '', ['width' => 400, 'showLines' => 2]);
$pdf->ezText("\nWithout table width:");
$pdf->ezTable($data, '', '', ['showLines' => 2]);

if (isset($_GET['d']) && $_GET['d']) {
    echo $pdf->ezOutput(true);
} else {
    $pdf->ezStream();
}
