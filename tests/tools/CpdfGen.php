<?php
namespace ROSPDF\tests\tools;

/**
 * @doesNotPerformAssertions
 */
class CpdfGen
{
    /**
     * List of excluded pdf generation scripts (blacklist)
     * @var array
     */
    public $excluded = [];

    /**
     * List of included pdf generation scripts (whitelist)
     * @var array
     */
    public $included = [];

    /**
     * ImageMagick binary path, get from env MAGICK_BINARY
     * @var string
     */
    public $magick;

    /**
     * ImageMagick compare binary path, get from env COMPARE_BINARY
     * @var string
     */
    public $compare;

    /**
     * Ghostscript binary path , get from env GS_BINARY
     * @var string
     */
    public $gs;

    public function __construct()
    {
        $this->magick = $this->getImageMagickBin();
        $this->compare = $this->getCompareBin();
        $this->gs = $this->getGhostScriptBin();
    }


    public function ensureDir($path)
    {
        if (!is_dir( $path )) {
            mkdir( $path, 0777, true );
        }
    }

    public function isExcluded($testName) 
    {
        return in_array($testName, $this->excluded);
    }

    public function isIncluded($testName) 
    {
        return in_array($testName, $this->included);
    }

    public function locateSystemBinary($name)
    {
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

    public function getCompareBin() {
        $compare = getenv('CONVERT_BINARY');
        if (empty($compare)) {
            $compare = $this->locateSystemBinary('compare');
        }

        return $compare;
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
        $command = $this->gs ." -d4 -dBATCH -dNOPAUSE -sDEVICE=pngalpha -dTextAlphaBits=1 -dGraphicsAlphaBits=1 -o \"$destFile\" -r300 \"$pdfFile\"";

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
            $redir = " > \"$outputFile\"";
        }
            
        // Always redirect error into stdout
        $fullCmd = $command .' 2>&1'.$redir;
        //echo "Executing $fullCmd\n";
        exec($fullCmd, $output, $returnValue);

        // Return the error output (stderr) in the $returnValue
        if ($returnValue == 0) {
            return implode(PHP_EOL, $output);
        }
        else {
            $outStr = implode("", $output);
            if ($outputFile) {
                // Errors were stored within the file
                return "$outStr\n".file_get_contents($outputFile);
            }
            else
                return $outStr;
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

    public function compareImages($imageFile1, $imageFile2, $diffFile) 
    {
        $subcommand = $this->compare . " -metric RMSE \"{$imageFile1}\" \"{$imageFile2}\" ";

        // Shell command to compare images using ImageMagick's compare
        $command =  "$subcommand null:  2>&1";
        //echo "Executing: $command\n";

        // Execute the shell command and capture the output
        exec($command, $output, $returnCode);

        // The command execution was successful and no differences
        if ($returnCode == 0) {
            return 0;
        }
        // The images are different
        elseif ($returnCode == 1) {

            $rating = -1;
            // Parse the output to extract the comparison result
            //$result = explode(" ", $output[0]);
            preg_match('/^([\d\.]+)\s/', $output[0], $matches);
            if (isset($matches[1])) {
                $rating = floatval($matches[1]);
            }

            // Force generate the diff file only when rating is not 0
            exec("$subcommand \"$diffFile\"");

            // Return the rating
            return $rating;
        } else {
            // Return an error code or handle the failure accordingly
            return -1;
        }
    }
    public function validatePDF($filepath)
    {
        $nullVal = stripos(PHP_OS, 'WIN') === 0 ? 'NUL' : '/dev/null';
        $cmd = "$this->gs -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dNOPROMPT -dSAFER -dQUIET -sOutputFile=$nullVal \"$filepath\"";

        $err = 0;
        $output = $this->executeShellCommand($cmd, $err);

        return $output;
    }

    public function runPdfScript($scriptFilepath, $outFile, &$output, $validateScriptCB=null)
    {
        $phpExec = PHP_BINARY;
        chdir(dirname($scriptFilepath));

        $retVal = 0;
        $output = $this->executeShellCommand("$phpExec \"$scriptFilepath\" ", $retVal, $outFile);

        if($validateScriptCB) {
            $validateScriptCB($scriptFilepath, $outFile, $retVal, $output);
        }

        return $retVal;
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

    public function cleanupDirectory($directory, $wildcard)
    {
        if (!$wildcard) {
            return;
        }

        $files = glob($directory . DIRECTORY_SEPARATOR . $wildcard);
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function rasterizePdfs($srcFolder, $dstFolder)
    {
        $files = $this->scanDirectory($srcFolder, 'pdf');
        echo "\nScanning $srcFolder for PDF files\n";
        foreach ($files as $file) {
            // whitelist check
            if($this->included && !$this->isIncluded(pathinfo($file, PATHINFO_FILENAME))) {
                continue;
            }
            // blacklist check
            if($this->isExcluded(pathinfo($file, PATHINFO_FILENAME))) {
                continue;
            }

            echo "Converting $file into png\n";
            $err ='';
            $this->convertPdfToPng($file, $dstFolder, $err);
        }
    }

    public function generatePdfs($srcFolder, $dstFolder, $validateScriptCB=null, $validatePdfCB=null)
    {
        chdir($srcFolder);
        $files = $this->scanDirectory($srcFolder, 'php');
        echo "\nScanning $srcFolder for script generation files\n";
        foreach ($files as $file) {
            // whitelist check
            if($this->included && !$this->isIncluded(pathinfo($file, PATHINFO_FILENAME))) {
                continue;
            }
            // blacklist check
            if($this->isExcluded(pathinfo($file, PATHINFO_FILENAME))) {
                continue;
            }
            echo "Generating PDF script $file\n";
            $pdfFilepath = $this->replaceExtension($file, 'pdf');
            $dstFilepath = $dstFolder . '/' . basename($pdfFilepath);
            $output = '';
            $retVal = $this->runPdfScript($file, $dstFilepath, $output, $validateScriptCB);

            if($validatePdfCB) {
                $validatePdfCB($dstFilepath);
            }
        }
    }

    public function compareDirectories($srcFolder, $dstFolder, $validationCB=null)
    {
        $srcFiles = $this->scanDirectory($srcFolder, 'png');
        $dstFiles = $this->scanDirectory($dstFolder, 'png');
        echo "\nScanning $srcFolder for PDF files\n";
        foreach ($srcFiles as $fname=>$file) {
            if (isset($dstFiles[$fname])) {
                $diff = $this->replaceExtension($dstFiles[$fname], 'diff.png');
                echo "Comparing  $file <-> {$dstFiles[$fname]}\n";
                $rating = $this->compareImages($file, $dstFiles[$fname], $diff);

                if($validationCB) {
                    $validationCB($file, $dstFiles[$fname], $rating);
                }
            }
        }
    }


}
