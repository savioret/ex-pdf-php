<?php
use PHPUnit\Framework\TestCase;

include_once "CpdfRenderTest.php";

class CpdfExamplesTest extends AbstractRenderTest
{

    public function __construct()
    {
        parent::__construct();

        $this->dirPath = dirname(__FILE__);

        $this->outDir = $this->dirPath . '/out/examples';
        $this->refDir = $this->dirPath . '/ref/examples';

        $this->ensureDir($this->outDir);
        $this->ensureDir($this->outDir.'/ref');
        $this->ensureDir($this->refDir);

        // Image example contains JPG compression which is not reliable to compare
        $this->excluded = ['image'];
    }

    public function test_Preparation() 
    {
        $this->initChecks();

        $this->assertTrue( file_exists($this->outDir), $this->outDir." could not be created");
        $this->assertTrue( file_exists($this->refDir), $this->refDir." could not be created");
        $this->assertTrue( file_exists($this->outDir.'/ref'), $this->outDir.'/ref'." could not be created");
    }

    /**
     * simple text output test
     */
    public function test_Examples()
    {
        print "Current directory:" . getcwd();

        $scriptsDir = $this->dirPath . '/../examples';
        
        // Generate reference PDFs (Do this only to create a reference checkpoint)
        //$this->generatePdfs($scriptsDir, $this->refDir);

        // Generate reference PNGs from reference PDFs
        $this->rasterizePdfs($this->refDir, $this->outDir."/ref");

        // Generate test PDFs
        $this->generatePdfs($scriptsDir, $this->outDir);

        // Generate test PNGs
        $this->rasterizePdfs($this->outDir, $this->outDir);

        $this->compareDirectories($this->outDir . "/ref", $this->outDir);

    }

}
