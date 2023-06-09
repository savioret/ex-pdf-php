<?php

use PHPUnit\Framework\TestCase;

class CpdfRenderTest extends TestCase
{
    private $output;

    /**
     * Current test generated directory
     * @var string
     */
    private $outDir;

    /**
     * Reference data directory to compare with
     * @var string
     */
    private $refDir;

    /**
     * Current file directory path
     * @var string
     */
    private $dirPath = '';

    /**
     * ImageMagick binary path
     * @var string
     */
    private $magick;

    /**
     * Ghostscript binary path
     * @var string
     */
    private $gs;

    public function __construct()
    {
        parent::__construct();

        $this->dirPath = dirname(__FILE__);
        print "\ndirpath:" . $this->dirPath;
        $this->outDir = $this->dirPath . '/out';
        $this->refDir = $this->dirPath . '/ref';
        
        $this->ensureDir($this->outDir);
        $this->ensureDir($this->refDir);
        $this->ensureDir($this->outDir.'/ref');


        $this->magick = $this->getImageMagickBin();
        $this->assertTrue(file_exists($this->magick), "ImageMagick binary does not exists ( got:$this->magick )");

        $this->gs = $this->getGhostScriptBin();
        $this->assertTrue(file_exists($this->magick), "GhostScript binary does not exists ( got:$this->gs )");
    }

    public function ensureDir($path)
    {
        if (!is_dir( $path )) {
            mkdir( $path );
        }
    }

    public function locateSystemBinary($name) {
        $binary = null;

        // Check for Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = shell_exec("where $name");
            if ($output !== null) {
                $lines = explode(PHP_EOL, trim($output));
                $binary = $lines[0];
            }
        }
        // Check for Linux or macOS
        else {
            $output = shell_exec("which $name");
            if ($output !== null) {
                $binary = trim($output);
            }
        }

        // Ensure the binary file exists
        if ($binary !== null && !file_exists($binary)) {
            $binary = null;
        }

        return $binary;
    }

    public function getImageMagickBin() {
        $magick = getenv('MAGICK_BINARY');
        if (empty($magick)) {
            $magick = $this->locateSystemBinary('magick');
        }

        return $magick;
    }

    public function getGhostScriptBin() {
        $gs = getenv('GS_BINARY');
        if (empty($gs)) {
            $gs = $this->locateSystemBinary('gs');
        }

        return $gs;
    }

    public function convertPdfToPng($pdfFile, $outputDir, &$err) {
        // Get the file name without extension
        $filename = pathinfo($pdfFile, PATHINFO_FILENAME);
        
        // Create the output directory if it doesn't exist
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        
        $destFile = "{$outputDir}/{$filename}_%02d.png";

        // Shell command to convert PDF to PNG using ImageMagick
        //$command = $this->magick . " -density 300 -depth 8 -quality 85 \"{$pdfFile}\" \"$destFile\"";
    
        // Prepare the Ghostscript command
        $command = $this->gs ." -dBATCH -dNOPAUSE -sDEVICE=pngalpha -dTextAlphaBits=1 -dGraphicsAlphaBits=1 -o ".escapeshellarg($destFile) ." -r300 ".escapeshellarg($pdfFile)."";

        // Execute the shell command
        $shellOutput = $this->executeShellCommand($command, $shellRet);
        if (!$shellRet) {
            $err = $shellOutput;
        }

        return $destFile;
    }

    public function executeShellCommand($command, &$returnValue, $outputFile = null) {
        $redir = $output = '';
        $returnValue = 0;

        if ($outputFile) {
            $redir = " > $outputFile";
        }
            
        // Always redirect error into stdout
        $fullCmd = $command .' 2>&1'.$redir;
        echo "Executing $fullCmd\n";
        exec($fullCmd, $output, $returnValue);

        // Return the error output (stderr) in the $returnValue
        if ($returnValue) {
            return implode(PHP_EOL, $output);
        }
        else {
            if ($outputFile) {
                // Errors were stored within the file
                return file_get_contents($outputFile);
            }
            else
                return implode("", $output);
        }
    }

    public function replaceExtension($file, $ext) 
    {
        $fname = pathinfo($file, PATHINFO_FILENAME);
        $path = dirname($file);

        $newFile = $fname . '.' . $ext;
        if($path) {
            $newFile = $path . '/' . $newFile;
        }

        return $newFile;
    }

    public function compareImages($imageFile1, $imageFile2, $diffFile) {
        // Shell command to compare images using ImageMagick
        $command = $this->magick . " compare -metric RMSE \"{$imageFile1}\" \"{$imageFile2}\" \"$diffFile\" 2>&1";
        
        // Execute the shell command and capture the output
        exec($command, $output, $returnCode);

        // Check if the command execution was successful
        if ($returnCode === 0) {
            // Parse the output to extract the comparison result
            $result = explode(" ", $output[0]);
            $rating = (float) $result[0];

            // Return the rating
            return $rating;
        } else {
            // Return an error code or handle the failure accordingly
            return -1;
        }
    }

    public function runPdfScript($scriptFilepath, $outFile) 
    {
        $phpExec = PHP_BINARY;
        chdir(dirname($scriptFilepath));

        $err = "";
        $output = $this->executeShellCommand("$phpExec ".escapeshellarg($scriptFilepath)." 2>&1", $err, $outFile);

        // ??? move this out
        $this->assertEquals($err, 0, "The shell command returned an error:\n".file_get_contents($scriptFilepath));
    }

    public function scanDirectory($folder, $extension)
    {
        $files = scandir($folder);
        $paths = [];
        foreach ($files as $file) {
            $filePath = $folder . '/' . $file;
            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === $extension) {
                $paths[basename($filePath)] = $filePath;
            }
        }

        return $paths;
    }

    public function rasterizePdfs($srcFolder, $dstFolder)
    {
        $files = $this->scanDirectory($srcFolder, 'pdf');
        echo "\nScanning $srcFolder for PDF files\n";
        foreach ($files as $file) {
            echo "Converting $file into png\n";
            $err ='';
            $this->convertPdfToPng($file, $dstFolder, $err);
        }
    }

    public function generatePdfs($srcFolder, $dstFolder)
    {
        chdir($srcFolder);
        $files = $this->scanDirectory($srcFolder, 'php');
        echo "\nScanning $srcFolder for script generation files\n";
        foreach ($files as $file) {
            echo "Generating PDF script $file\n";
            $pdfFilepath = $this->replaceExtension($file, 'pdf');
            $dstFilepath = $dstFolder . '/' . basename($pdfFilepath);
            $this->runPdfScript($file, $dstFilepath);
        }
    }

    public function compareDirectories($srcFolder, $dstFolder)
    {
        $srcFiles = $this->scanDirectory($srcFolder, 'png');
        $dstFiles = $this->scanDirectory($dstFolder, 'png');
        echo "\nScanning $srcFolder for PDF files\n";
        foreach ($srcFiles as $fname=>$file) {
            if (isset($dstFiles[$fname])) {
                $diff = $this->replaceExtension($dstFiles[$fname], 'diff.png');
                $rating = $this->compareImages($file, $dstFiles[$fname], $diff);

                $this->assertEquals($rating, 0, "Comparison of $file and {$dstFiles[$fname]} failed");
            }
        }
    }

    /**
     * simple text output test
     */
    public function test_Examples()
    {
        print "Current directory:" . getcwd();

        $scriptsDir = $this->dirPath . '/../examples0';
        
        // Generate reference PDFs
        $this->generatePdfs($scriptsDir, $this->refDir);

        // Generate reference PNGs
        $this->rasterizePdfs($this->refDir, $this->outDir."/ref");

        
        // Generate test PDFs
        $this->generatePdfs($scriptsDir, $this->outDir);

        // Generate test PNGs
        $this->rasterizePdfs($this->outDir, $this->outDir);

        $this->compareDirectories($this->outDir . "/ref", $this->outDir);

        // chdir($this->dirPath.'/../examples');
        // $outFile = "{$this->outDir}/columns.pdf";
        // $output = $this->executeShellCommand("$phpExec columns.php 2>&1", $err, $outFile);

        // $this->runPdfScript($filepath, $outFile))

        // $this->assertEquals($err, 0, "The shell command returned an error:\n".file_get_contents("{$this->outDir}/columns.pdf"));
        // if($err) {
        //     echo $output;
        // } else {
        //     $this->assertTrue(file_exists($outFile));
        //     $destPath = dirname($outFile);// .'/'. pathinfo($outFile, PATHINFO_FILENAME).".png";
        //     $destFile = $this->convertPdfToPng($outFile, $destPath, $err);
        //     $this->assertEquals($err, "", $err);
        //     $this->assertTrue(file_exists($destFile), "The PNG file was not created");
        // }
    }

}
