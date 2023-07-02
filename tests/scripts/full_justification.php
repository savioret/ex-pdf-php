<?php
include __DIR__.'/../../src/Cezpdf.php';

$pdf = new CezPDF('a4');
$pdf->selectFont('Helvetica');


$pdf->ezColumnsStart(['num' => 2, 'gap'=>20]);
$options = ['justification' => 'full'];

// Testing break lines should be considered as last lines
$text0 = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tristique nec odio id euismod. Curabitur euismod ex volutpat bibendum eleifend. Donec pretium pretium nibh, at consectetur turpis porta et. Mauris pretium risus eget feugiat pellentesque.\nQuisque egestas sodales turpis, et euismod nisl vulputate at. Etiam convallis, turpis eu suscipit viverra, nulla ipsum vehicula metus, vitae posuere metus urna vitae dolor. Aliquam commodo non mi rutrum pretium. Ut facilisis turpis id quam tempus condimentum. In dapibus non est ac facilisis.\n\nNulla vulputate suscipit ligula, vel finibus arcu ullamcorper id. Duis ut facilisis felis. Nam ullamcorper nunc eget sapien placerat aliquet.\nEtiam nec elit risus. In vitae neque et felis imperdiet rutrum. Sed sed metus erat. Duis sit amet lacus eu nisl fringilla venenatis.";
$pdf->ezText($text0, 0, $options);
//$pdf->ezText("-------");
$text1 = "\nLorem ipsum dolor sit amet, vitae posuere metus urna et vitae dolor consectetur adipiscing tristique nec odio id euismod."
."\nCurabitur euismod ex volutpat bibendum eleifend."
."\nDonec pretium pretium nibh."
."\nAt consectetur turpis porta et.\n";
$pdf->ezText($text1, 0, $options);


for ($n=1;$n<15;$n+=2) {
    $text = str_repeat("lor em ipsum ", $n);
    $text[strlen($text) - 1] = '.';
    $pdf->ezText($text, 0, $options);
    $pdf->ezText("-------");
}

$pdf->ezNewPage();

// Inside tables
$myoptions = [
    'width' => $pdf->ez['pageWidth'] - $pdf->ez['rightMargin'] - $pdf->ez['leftMargin'],
    'showHeadings' => 0,
    'colGap' => 0,
    'rowGap' => 0,
    'cols' => [['justification'=>'full']]
];

$data = [[$text0], [$text1]];
$pdf->ezTable($data, null, "", $myoptions);



for ($n=3;$n<20;$n+=2) {
    $text = str_repeat("lor ipsum ", $n);
    $text[strlen($text) - 1] = '.';
    $pdf->ezText($text, 0, $options);
    $pdf->ezText("-------");
}

$pdf->ezColumnsStop();

if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
