<?php
namespace ROSPDF\tests\tools;

require_once __DIR__ . '/../../vendor/autoload.php';

$scripts = [
    'examples' => '\ROSPDF\tests\CpdfExamplesTest',
    'readme' => '\ROSPDF\tests\CpdfReadmeTest',
    'features' => '\ROSPDF\tests\CpdfFeaturesTest',
];

$folder = __DIR__ . '/..';

if (empty($tests)) {
    $tests = array_keys($scripts);
}
else {
    $tests = array_slice($argv, 1);
}

// Iterate over command-line arguments
foreach ($tests as $test) {

    if (!isset($scripts[$test])) {
        echo "Invalid test $test\n";
        continue;
    }

    $className = $scripts[$test];

    // Create an instance of the class and call the generatePdfs() method
    $generator = new $className();
    if ($generator instanceof \ROSPDF\tests\CpdfRenderBase) {
        $generator->buildReferencePdfs();
    } else {
        echo "Class '{$className}' does not implement CpdfRenderBase.\n";
    }
}