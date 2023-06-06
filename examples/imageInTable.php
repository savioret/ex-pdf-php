<?php
include_once '../src/Cezpdf.php';

$pdf = new Cezpdf('a4');

$pdf->selectFont('Helvetica');

$image = '../ros.jpg';
// test gif file
//$image = 'images/test_alpha.gif';

$data = array(
    ['num' => 1, 'name' => 'gandalf', 'type' => '<C:image:'.$image.' 90>'],
    ['num' => 4, 'name' => 'saruman', 'type' => 'baddude', 'url' => 'https://github.com/rospdf/pdf-php/blob/master/examples/images/test_alpha.png'],
    ['num' => 5, 'name' => 'sauron', 'type' => '<C:image:'.urlencode($image).' 90>'],
    ['num' => 6, 'name' => 'sauron', 'type' => '<C:image:'.$image.'><C:image:'.$image.' 90>'."\nadadd"],
    ['num' => 8, 'name' => 'sauron', 'type' => '<C:image:'.$image.' 90>'],
    ['num' => 10, 'name' => 'sauron', 'type' => '<C:image:'.$image.' 50>'],
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
