<?php
namespace ROSPDF\tests;


class CpdfReadmeTest extends CpdfRenderBase
{
    public function __construct()
    {
        parent::__construct();

        $this->dirPath = __DIR__;

        $this->outDir = $this->dirPath . '/out/readme';
        $this->refDir = $this->dirPath . '/ref/readme';

        $this->ensureDir($this->outDir);
        $this->ensureDir($this->outDir.'/ref');
        $this->ensureDir($this->refDir);

        $this->scriptsDir = $this->dirPath . '/..';

        // Force only this script to be executed
        $this->gen->included = ['readme'];
    }

    public function test_Preparation() 
    {
        $this->initChecks();

        $this->assertTrue( file_exists($this->outDir), $this->outDir." could not be created");
        $this->assertTrue( file_exists($this->refDir), $this->refDir." could not be created");

        $this->cleanupDirectory($this->outDir, '*.png');
        $this->cleanupDirectory($this->outDir, '*.pdf');
        $this->cleanupDirectory($this->outDir.'/ref', '*.pdf');
    }

    /**
     * simple text output test
     */
    public function test_Scripts()
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
