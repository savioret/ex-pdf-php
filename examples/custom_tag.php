<?php
include_once '../src/Cezpdf.php';

class Creport extends Cezpdf
{

    public function __construct($p, $o = 'portrait', $t = 'none', $op = [])
    {
        parent::__construct($p, $o, $t, $op);

        // register a new tag accepting a float argument
        //$this->registerTag('superindex');//, '(:?:[\d\.])*');
        $this->registerTag('superindex', '(?::[\d\.]+)?');

        // Add the tag as allowed
        $this->allowTags(['superindex']);
    }
    /**
     * callback function for superindex.
     *
     * **Example**<br>
     * <pre>
     * $pdf->ezText('X<c:superindex>2</c:superindex>');
     * </pre>
     *
     * @param $info
     */
    public function superindex(&$info)
    {
        $override = []; // overriden data

        switch ($info['status']) {

            // Addtext is preparing the text line and a 'superindex' callback was found
            // We store current y position and size to restore them later
            case 'prepare_start':
                $factor = isset($info['p']) ? floatval($info['p']) : 0.5;
                $info['saved_factor'] = $factor;
                $info['saved_y'] = $info['y'];

                // override the size in the preparation to allow correct text calculation
                $override['size'] = $info['size'] * $factor;
                break;

            // This is the real processing of text
            case 'start':
                $newsize = $info['size'] * $info['saved_factor'];
                // Get displacement of the text
                $deltaY = $this->getFontHeight($info['size'])*0.6 - $this->getFontHeight($newsize)*0.4;
                $newy = $info['saved_y'] + $deltaY;
                // Override Y-position and text size
                $override['y'] = $newy;
                $override['size'] = $newsize;
                break;

            // Restore values after the closing tag
            case 'end':
            case 'prepare_end':
                $override['size'] = $info['orgSize'];
                $override['y'] = $info['saved_y'];
                break;
        }

        return $override;
    }
}

$pdf = new Creport('a4', 'portrait');


$pdf->selectFont('Helvetica');
//$pdf->selectFont('Times-Roman');
$pdf->allowTags(['superindex']);

$pdf->ezSetFontSize(200);
$pdf->ezText("n<c:superindex>2</c:superindex>", 0, ['justification' => 'center']);
$pdf->ezText("X<c:superindex>y</c:superindex>", 0, ['justification' => 'center']);
$pdf->ezText("a<c:superindex:0.2>c</c:superindex>", 0, ['justification' => 'center']);

if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
