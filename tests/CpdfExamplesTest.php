<?php
namespace ROSPDF\tests;


class CpdfExamplesTest extends CpdfRenderBase
{

    public function __construct()
    {
        parent::__construct();

        $this->dirPath = __DIR__;

        $this->outDir = $this->dirPath . '/out/examples';
        $this->refDir = $this->dirPath . '/ref/examples';

        $this->ensureDir($this->outDir);
        $this->ensureDir($this->outDir.'/ref');
        $this->ensureDir($this->refDir);

        $this->scriptsDir = $this->dirPath . '/../examples';

        // Image example contains JPG compression which is not reliable to compare
        $this->gen->excluded = ['image'];
    }

    protected function setUp() : void
    {
        $this->initChecks();

        $this->assertTrue( file_exists($this->outDir), $this->outDir." could not be created");
        $this->assertTrue( file_exists($this->refDir), $this->refDir." could not be created");
        $this->assertTrue( file_exists($this->outDir.'/ref'), $this->outDir.'/ref'." could not be created");

        $this->cleanupDirectory($this->outDir, '*.png');
        $this->cleanupDirectory($this->outDir, '*.pdf');
        $this->cleanupDirectory($this->outDir.'/ref', '*.pdf');

        parent::setUp();
    }

    /**
     * simple text output test
     */
    public function test_Examples()
    {
        // Generate reference PNGs from reference PDFs
        $this->rasterizePdfs($this->refDir, $this->outDir."/ref");

        // Generate test PDFs
        $this->generatePdfs($this->scriptsDir, $this->outDir);

        // Generate test PNGs
        $this->rasterizePdfs($this->outDir, $this->outDir);

        $this->compareDirectories($this->outDir . "/ref", $this->outDir);

    }

}
