<?php
namespace ROSPDF\tests;

use PHPUnit\Framework\TestCase;
use ROSPDF\tests\tools\CPdfGen;

/**
 * @doesNotPerformAssertions
 */
class CpdfRenderBase extends TestCase
{
    /**
     * Where to put generated pdf and png files
     * @var string
     */
    protected $outDir;

    /**
     * Reference data directory to compare with generated files
     * @var string
     */
    protected $refDir;

    /**
     * Current file directory path
     * @var string
     */
    protected $dirPath = '';

    /**
     * Directory containing the PDF generation scripts for this test
     * @var string
     */
    protected $scriptsDir = '';

    /**
     * Helper class to manipulate pdfs
     * @var tools\CPdfGen
     */
    protected $gen = [];


    public function __construct()
    {
        parent::__construct();

        $this->gen = new CPdfGen();
    }

    public function initChecks()
    {
        $this->assertTrue(file_exists($this->gen->magick), "ImageMagick binary does not exist ( got:{$this->gen->magick} )");
        $this->assertTrue(file_exists($this->gen->compare), "ImageMagick compare binary does not exist ( got:{$this->gen->compare} )");
        $this->assertTrue(file_exists($this->gen->gs), "GhostScript binary does not exist ( got:{$this->gen->gs} )");
    }

    public function ensureDir($path)
    {
       return $this->gen->ensureDir($path);
    }

    public function cleanupDirectory($directory, $wildcard)
    {
        return $this->gen->cleanupDirectory($directory, $wildcard);
    }

    public function rasterizePdfs($srcFolder, $dstFolder)
    {
        $this->gen->rasterizePdfs($srcFolder, $dstFolder);
    }

    public function assertValidatePdf($filepath)
    {
        $pdfCheck = $this->gen->validatePDF($filepath);
        $this->assertEquals("", $pdfCheck, "PDF Validation of $filepath failed");
    }

    public function assertValidatePdfScript($scriptFilepath, $outputFile, $retVal, $output)
    {
        $this->assertEquals(0, $retVal, "The script generation of $scriptFilepath returned an error:\n$output\n".file_get_contents($outputFile)."\n");
    }

    public function assertCompareImages($filepath1, $filepath2, $rating)
    {
        $this->assertEquals(0, $rating, "Comparison of $filepath1 and $filepath2 failed with rating $rating");
    }

    public function generatePdfs($srcFolder, $dstFolder, $validate=true)
    {
        $this->gen->generatePdfs($srcFolder, $dstFolder, [$this, 'assertValidatePdfScript'], [$this, 'assertValidatePdf']);
    }

    public function compareDirectories($srcFolder, $dstFolder)
    {
        $this->gen->compareDirectories($srcFolder, $dstFolder, [$this, 'assertCompareImages']);
    }

    /**
     * Creates reference documents for later comparing
     * @return void
     */
    public function buildReferencePdfs()
    {
        if ($this->refDir && $this->scriptsDir) {
            // Generate reference PDFs
            $this->generatePdfs($this->scriptsDir, $this->refDir);
        }
        else {
            echo "Scripts directory and output reference directory must be properly set";
        }
    }

}
