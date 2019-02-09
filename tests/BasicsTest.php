<?php

/*
 * Bear Framework addon test utilities
 * https://github.com/bearframework/addon-tests
 * Copyright (c) Amplilabs Ltd.
 * Free to use under the MIT license.
 */

use BearFramework\AddonTests\PHPUnitTestCase;

/**
 * 
 */
class BasicsTest extends PHPUnitTestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testApp()
    {
        $app = $this->getApp();
        $this->assertTrue($app instanceof \BearFramework\App);
    }

    /**
     * @runInSeparateProcess
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
     * @runInSeparateProcess
     */
    public function testIntitializeAppTwiceException()
    {
        $this->expectException('\Exception');
        $this->initializeApp();
    }

    /**
     * 
     */
    public function testAppInstance1()
    {
        $app = $this->getApp();
        $this->assertTrue($app instanceof \BearFramework\App);
        $app->key1 = 'value1';
    }

    /**
     * 
     */
    public function testAppInstance2()
    {
        $app = $this->getApp();
        $this->assertTrue($app instanceof \BearFramework\App);
        $this->assertEquals($app->key1, 'value1');
    }

}
