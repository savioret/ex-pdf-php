<?php
date_default_timezone_set('UTC');

include dirname(__FILE__).'/../../src/Cezpdf.php';

$pdf = new Cezpdf('a4', 'portrait');

$pdf->ezSetMargins(20, 20, 20, 20);

$y = $pdf->y;


# text with angle
$pdf->addText(150,$y,10,"the quick brown fox <b>jumps</b>
<i>over</i> the <u>lazy dog</u>!",0, 'left', 10);

$y -= 50;

# styled text
$pdf->addText($pdf->ez['leftMargin'], $y, 10, "<b>bold text</b>
<i>italic text</i>
<b><i>bold italic text<i></b>");


$pdf->addText($pdf->ez['pageWidth']-$pdf->ez['rightMargin'], $y, 10, "<b>bold text</b>
<i>italic text</i>
<b><i>bold italic text<i></b>", 0, 'right');

$pdf->addText($pdf->ez['pageWidth']/2, $y, 10, "<b>bold text</b>
<i>italic text</i>
<b><i>bold italic text<i></b>", 0, 'center');

$y -= 20;

$text = "<b>bold text</b> <i>italic text</i> <b><i>bold italic text<i></b> <b>bold text</b> <i>italic text</i> <b><i>bold italic text<i></b> <b>bold text</b> <i>italic text</i> <b><i>bold italic text<i></b> <b>bold text</b> <i>italic text</i> <b><i>bold italic text<i></b> <b>bold text</b> <i>italic text</i> <b><i>bold italic text<i></b> <b>bold text</b> <i>italic text</i> <b><i>bold italic text<i></b> <b>bold text</b> <i>italic text</i> <b><i>bold italic text<i></b>.";
while ($text) {
    $text = $pdf->addText($pdf->ez['leftMargin'], $y, 10, $text, $pdf->ez['pageWidth'] - $pdf->ez['rightMargin'] - $pdf->ez['leftMargin'], 'full');
    $y -= 15;
}


if (isset($_GET['d']) && $_GET['d']) {
    echo $pdf->ezOutput(true);
} else {
    $pdf->ezStream(['compress' => 0]);
}
