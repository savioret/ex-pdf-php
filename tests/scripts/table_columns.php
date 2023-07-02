<?php
include __DIR__.'/../../src/Cezpdf.php';

$pdf = new CezPDF('a4');
$pdf->selectFont('Helvetica');

$myoptions = array(
    'width' => $pdf->ezContentWidth(),
    'showHeadings' => 0,
);


for ($cols = 1; $cols < 10; $cols++) {
    for ($n=1;$n<20;$n+=4) {
        $text = str_repeat("lorem ipsum ", ceil($n/($cols/2)));
        $row = [];
        for ($r = 0; $r < $cols; $r++) {
            $row[] = $text;
        }
        $rows = [$row];
        //$pdf->ezText("Len:" . strlen($text));
        $pdf->ezTable($rows, null, "", $myoptions);
    }
}

if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
