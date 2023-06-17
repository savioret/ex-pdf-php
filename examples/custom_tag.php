<?php
include_once '../src/Cezpdf.php';

class Creport extends Cezpdf
{
    protected $superscriptOrgY;

    public function __construct($p, $o = 'portrait', $t = 'none', $op = [])
    {
        parent::__construct($p, $o, $t, $op);

        // register a new tag accepting a float argument
        //$this->registerTag('superscript');//, '(:?:[\d\.])*');
        $this->registerTag('superscript', '(?::[\d\.]+)?');

        // Add the tag as allowed
        $this->allowTags(['superscript']);
    }
    /**
     * callback function for superscript.
     *
     * **Example**<br>
     * <pre>
     * $pdf->ezText('X<c:superscript>2</c:superscript>');
     * </pre>
     *
     * @param $info
     */
    public function superscript(&$info)
    {
        $override = []; // overriden data

        switch ($info['status']) {

            // Addtext is preparing the text line and a 'superscript' callback was found
            // We store current y position and size to restore them later
            case 'prepare_start':
                // get optional factorparameter as a float value
                $factor = isset($info['p']) ? floatval($info['p']) : 0.5;

                // We can pass data between prepare_start -> start and prepare_end -> end
                $info['saved_factor'] = $factor;
                $this->superscriptOrgY = $info['y'];

                // override the size in the preparation to allow correct text calculation
                $override['size'] = $info['size'] * $factor;
                break;

            // This is the real processing of text
            case 'start':
                $newsize = $info['size'] * $info['saved_factor'];
                // Get displacement of the text
                $deltaY = $this->getFontHeight($info['size'])*0.6 - $this->getFontHeight($newsize)*0.4;
                $newy = $info['y'] + $deltaY;
                // Override Y-position and text size
                $override['y'] = $newy;
                $override['size'] = $newsize;
                break;

            // Restore values after the closing tag
            case 'end':
            case 'prepare_end':
                $override['size'] = $info['orgSize'];
                $override['y'] = $this->superscriptOrgY;
                break;
        }

        return $override;
    }
}

$pdf = new Creport('a4', 'portrait');


$pdf->selectFont('Helvetica');
//$pdf->selectFont('Times-Roman');
$pdf->allowTags(['superscript']);

$pdf->ezSetFontSize(200);
$pdf->ezText("n<c:superscript>2</c:superscript>", 0, ['justification' => 'center']);
$pdf->ezText("X<c:superscript>y</c:superscript>", 0, ['justification' => 'center']);
$pdf->ezText("a<c:superscript:0.2>c</c:superscript>", 0, ['justification' => 'center']);

if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
