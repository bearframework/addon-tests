<?php

/*
 * Bear Framework addon test utilities
 * https://github.com/bearframework/addon-tests
 * Copyright (c) Amplilabs Ltd.
 * Free to use under the MIT license.
 */

use BearFramework\AddonTests\PHPUnitTestCase;

/**
 * @runTestsInSeparateProcesses
 */
class BasicsTest extends PHPUnitTestCase
{

    /**
     * 
     */
    public function testApp()
    {
        $app = $this->getApp();
        $this->assertTrue($app instanceof \BearFramework\App);
    }

    /**
     * 
     */
    public function testMakeFilesAndDirs()
    {
        $tempDir = $this->getTempDIr();
        $this->makeSampleFile($tempDir . '/1.jpg', 'jpg');
        $this->assertTrue(is_file($tempDir . '/1.jpg'));
        $this->makeDir($tempDir . '/dir1');
        $this->assertTrue(is_dir($tempDir . '/dir1'));
    }

    /**
     * 
     */
    public function testIntitializeAppTwiceException()
    {
        $this->initializeApp();
        $this->expectException('\Exception');
        $this->initializeApp();
    }

}
