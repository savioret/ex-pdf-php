<?php
include_once '../src/Cezpdf.php';

$pdf = new CezPDF('a4');
$pdf->selectFont('Helvetica');
$contentWidth = $pdf->ez['pageWidth'] - $pdf->ez['leftMargin'] - $pdf->ez['rightMargin'];

$pdf->ez['fontSize'] = 10;
$myoptions = array(
    'showHeadings'=>0,
    'cols' => [['width' =>44]]
);
$txt = "This text serves as a practical demonstration of how <c:fontsize:50>font size</c:fontsize> can be adjusted within a paragraph. By manipulating the size, you can effectively <c:fontsize:25><b>highlight</b></c:fontsize> specific information or create a visual hierarchy that aids in comprehension. Experimenting with <c:fontsize:6>different font sizes</c:fontsize> allows you to strike a balance between readability and conveying emphasis.";

$pdf->ezText($txt);

$pdf->ezText("\n<b>Using text spacing:</b>");
$pdf->ezText($txt, 0, ['spacing' => 5]);

$pdf->ezNewPage();
$pdf->ezText("\n<b>Change size inside a table, using text spacing:</b>");
$tableprops = [
    'rowGap' => 0,
    'colGap' => 0,
    'showHeadings' => 0
];

$data = [[$txt],[$txt]];
$pdf->ezTable(
    $data,
    '',
    '',
    $tableprops + ['width' => $contentWidth, 'cols' => [['spacing' => 3,'justification'=>'full']]]
);


$txt2 = "This isj text to show how <c:fontsize:80>full justification</c:fontsize> <c:fontsize:7>  behaves in a paragraph.</c:fontsize>The expected behavior is that the first and the last lines are not fully aligned. This is text to show how full justification behaves in a paragraph. The expected behavior is that the first and the last lines are not fully aligned.";
$pdf->ezText("\n\n<b>Changing size of table headers:</b>\n");

$data = [
    ['<c:fontsize:30>numg</c:fontsize>' => 1, 'name' => 'gandalf', 'type' => 'wizard']
    ,
    [
        '<c:fontsize:30>numg</c:fontsize>' => 2,
        'name' => 'bilbo',
        'type' => 'hobbit',
        'url' => 'https://github.com/rospdf/pdf-php'
    ]
    ,
    ['<c:fontsize:30>numg</c:fontsize>' => 3, 'name' => 'frodo', 'type' => 'hobbit']
    ,
];

$pdf->ezTable($data, '', '', [
    'gridlines' => EZ_GRIDLINE_DEFAULT,
    'shadeHeadingCol' => [0.6, 0.6, 0.5],
    'alignHeadings' => 'center',
    'width' => 400,
    'cols' => [
        'name' => ['bgcolor' => [0.9, 0.9, 0.7]],
        'type' => ['bgcolor' => [0.6, 0.4, 0.2]]
    ]
]);


$pdf->ezNewPage();

$txt = '';
for ($x = 3; $x < 62; $x++) {
    $txt .= "<c:fontsize:$x>Font$x</c:fontsize>size ";
}

$pdf->ezText($txt);


if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
