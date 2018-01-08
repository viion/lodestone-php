<?php
/**
 *
 * This is an automated tester, it obtains all methods in
 * the API class and calls any that have "@test" on them.
 *
 * You can provide test parameters like so: "@test x,y,z"
 * a max of 3 currently. If you do "@test ." it means no
 * parameters (the dot is required for regex)
 *
 * If a response can typically be empty (eg no friends or
 * followers) then place "@softfail true" in the docblock
 *
 */

// composer auto loader
require __DIR__.'/../vendor/autoload.php';

// enable logging
define('LOGGER_ENABLED', true);
$logger = new \Lodestone\Modules\Logging\Logger();
$logger->write('TESTS',__LINE__,'Running tests ...');

// api
$api = new \Lodestone\Api;
$apiMethods = get_class_methods($api);
$apiTestsCount = count($apiMethods);
$testResultArray = [];
$testResult = [
    'passed' => 0,
    'failed' => 0,
    'accept' => 0,
];

// print number of api methods
$logger->write('TESTS',__LINE__,$apiTestsCount .' Tests');


// get reflector
$reflector = new ReflectionClass(get_class($api));

// loop through methods
$start = microtime(true);
foreach(get_class_methods($api) as $method) {
    // get method docs
    $methodDocs = $reflector->getMethod($method)->getDocComment();

    // parse fields
    if (preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $methodDocs, $matches)){
        $result = array_combine($matches[1], $matches[2]);
    }

    // is softfail?
    $isSoftFail = isset($result['softfail']) ? true : false;

    // if test instructions
    if (isset($result['test']) && $result['test']) {
        // remove dots
        $test = str_ireplace('.', null, trim($result['test']));

        // inform
        $logger->write('TESTS',__LINE__,sprintf('Testing method: %s with data: %s', $method, $test));
        $test = explode(',', $test);

        // bit hacky, need to figure out a cleaner way
        $data = false;
        switch(count($test)) {
            case 3:
                try {
                    $data = $api->$method($test[0], $test[1], $test[2]);
                } catch (\Lodestone\Validator\Exceptions\ValidationException $vex) {
                    $logger->write('TESTS',__LINE__,sprintf('Exception: %s', $vex->getMessage()));
                }
                break;

            case 2:
                try {
                    $data = $api->$method($test[0], $test[1]);
                } catch (\Lodestone\Validator\Exceptions\ValidationException $vex) {
                    $logger->write('TESTS',__LINE__,sprintf('Exception: %s', $vex->getMessage()));
                }
                break;

            case 1:
                try {
                    $data = $api->$method($test[0]);
                } catch (\Lodestone\Validator\Exceptions\ValidationException $vex) {
                    $logger->write('TESTS',__LINE__,sprintf('Exception: %s', $vex->getMessage()));
                }
                break;

            case 0:
                try {
                    $data = $api->$method();
                } catch (\Lodestone\Validator\Exceptions\ValidationException $vex) {
                    $logger->write('TESTS',__LINE__,sprintf('Exception: %s', $vex->getMessage()));
                }
                break;
        }

        if (!$data) {
            $testResult['accept']++;
            continue;
        }

        // get pass status
        $status = ($data ? 'PASSED' : ($isSoftFail ? 'ACCEPT' : 'FAILED'));

        // increment test count
        $testResult[strtolower($status)]++;

        // add to result array
        $testResultArray[$method] = $status;

        // write
        $logger->write('TESTS',__LINE__,$status .' - '. $method);
    }
}
$finish = microtime(true);

// write report
$logger->write('TESTS',__LINE__,'Passed: '. $testResult['passed'] .'/'. $apiTestsCount);
$logger->write('TESTS',__LINE__,'Failed: '. $testResult['failed'] .'/'. $apiTestsCount);
$logger->write('TESTS',__LINE__,'Accept: '. $testResult['accept'] .'/'. $apiTestsCount);
$logger->write('TESTS',__LINE__,'Results:');
foreach($testResultArray as $method => $status) {
    $logger->write('TESTS',__LINE__,'   '. $method .' = '. $status);
}

// write timestamps
$duration = round($finish - $start, 8);
$logger->write('TESTS',__LINE__,'Duration: '. $duration);