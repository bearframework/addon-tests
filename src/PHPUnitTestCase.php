<?php

/*
 * Bear Framework addon test utilities
 * https://github.com/bearframework/addon-tests
 * Copyright (c) Amplilabs Ltd.
 * Free to use under the MIT license.
 */

namespace BearFramework\AddonTests;

/**
 *
 */
class PHPUnitTestCase extends \PHPUnit\Framework\TestCase
{

    private static $app = null;
    private static $tempDirs = [];

    /**
     * Overwrite this method to initialize the app yourself.
     *
     * @param bool $setLogger
     * @param bool $setDataDriver
     * @param bool $setCacheDriver
     * @param bool $addAddon
     * @return \BearFramework\App
     */
    protected function initializeApp(bool $setLogger = true, bool $setDataDriver = true, bool $setCacheDriver = true, bool $addAddon = true): \BearFramework\App
    {
        $app = new \BearFramework\App();
        $app->request->base = 'http://example.com/';
        $app->request->method = 'GET';

        if ($setLogger) {
            $logsDir = $this->getTempDir();
            $this->makeDir($logsDir);
            $app->logs->useFileLogger($logsDir);
        }

        if ($setDataDriver) {
            $dataDir = $this->getTempDir();
            $this->makeDir($dataDir);
            $app->data->useFileDriver($dataDir);
        }

        if ($setCacheDriver) {
            $app->cache->useAppDataDriver();
        }

        if ($addAddon) {
            $addonID = $this->getTestedAddonID();
            if ($addonID !== null) {
                $app->addons->add($addonID);
            }
        }

        return $app;
    }

    /**
     *
     * @param string $indexContent
     * @return void
     * @throws \Exception
     */
    protected function makeContext(string $indexContent = '<?php'): void
    {
        $app = $this->getApp();
        $dir = $this->getTempDir();
        $this->makeDir($dir);
        $this->makeFile($dir . '/index.php', $indexContent);
        $app->contexts->add($dir);
    }

    /**
     *
     * @return \BearFramework\App
     */
    public function getApp(): \BearFramework\App
    {
        if (self::$app === null) {
            self::$app = $this->initializeApp();
        }
        return self::$app;
    }

    /**
     *
     * @return string
     */
    protected function getTempDir(): string
    {
        $dir = sys_get_temp_dir() . '/bearframework-addon-unittests/' . md5(uniqid());
        $this->makeDir($dir);
        self::$tempDirs[] = $dir;
        return $dir;
    }

    /**
     *
     * @param string $dir
     * @return void
     */
    protected function makeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     *
     * @param string $filename
     * @param string $content
     * @return void
     */
    protected function makeFile(string $filename, string $content): void
    {
        $pathinfo = pathinfo($filename);
        if (isset($pathinfo['dirname']) && $pathinfo['dirname'] !== '.') {
            $this->makeDir($pathinfo['dirname']);
        }
        file_put_contents($filename, $content);
    }

    /**
     *
     * @param string $dir
     * @return void
     */
    protected function deleteDir(string $dir): void
    {
        $dir = rtrim($dir, '/\\');
        if (is_dir($dir)) {
            $handle = opendir($dir);
            if ($handle) {
                while ($file = readdir($handle)) {
                    if ($file != '.' && $file != '..') {
                        if (!is_dir($dir . '/' . $file)) {
                            unlink($dir . '/' . $file);
                        } else {
                            $this->deleteDir($dir . '/' . $file);
                        }
                    }
                }
                closedir($handle);
                rmdir($dir);
            }
        }
    }

    /**
     *
     * @param string $filename
     * @param string $type
     * @return void
     * @throws \Exception
     */
    protected function makeSampleFile(string $filename, string $type): void
    {
        if ($type === 'png') {
            $this->makeFile($filename, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAGQAAABGCAIAAAC15KY+AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH4AIECCIIiEjqvwAAAB1pVFh0Q29tbWVudAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAAAd0lEQVR42u3QMQEAAAgDILV/51nBzwci0CmuRoEsWbJkyZKlQJYsWbJkyVIgS5YsWbJkKZAlS5YsWbIUyJIlS5YsWQpkyZIlS5YsBbJkyZIlS5YCWbJkyZIlS4EsWbJkyZKlQJYsWbJkyVIgS5YsWbJkKZAl69sC1G0Bi52qvwoAAAAASUVORK5CYII='));
        } elseif ($type === 'jpg' || $type === 'jpeg') {
            $this->makeFile($filename, base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wCEAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/CABEIAEYAZAMBEQACEQEDEQH/xAAVAAEBAAAAAAAAAAAAAAAAAAAACf/aAAgBAQAAAACL4AAAAAAAAAAAAAAAAAAB/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAn/2gAIAQIQAAAAlOAAAAAAAAAAAAAAAAAAAf/EABUBAQEAAAAAAAAAAAAAAAAAAAAK/9oACAEDEAAAAL+AAAAAAAAAAAAAAAAAAAD/xAAUEAEAAAAAAAAAAAAAAAAAAABg/9oACAEBAAE/AGv/xAAUEQEAAAAAAAAAAAAAAAAAAABg/9oACAECAQE/AGv/xAAUEQEAAAAAAAAAAAAAAAAAAABg/9oACAEDAQE/AGv/2Q=='));
        } elseif ($type === 'gif') {
            $this->makeFile($filename, base64_decode('R0lGODdhZABGAPAAAP8AAAAAACwAAAAAZABGAAACXISPqcvtD6OctNqLs968+w+G4kiW5omm6sq27gvH8kzX9o3n+s73/g8MCofEovGITCqXzKbzCY1Kp9Sq9YrNarfcrvcLDovH5LL5jE6r1+y2+w2Py+f0uv2Oz5cLADs='));
        } elseif ($type === 'webp') {
            $this->makeFile($filename, base64_decode('UklGRlYAAABXRUJQVlA4IEoAAADQAwCdASpkAEYAAAAAJaQB2APwA/QACFiY02iY02iY02iY02iYywAA/v9vVv//8sPx/Unn/yxD///4npzeIqeV//EyAAAAAAAAAA=='));
        } elseif ($type === 'bmp') {
            $this->makeFile($filename, base64_decode('Qk16AAAAAAAAAHYAAAAoAAAAAQAAAAEAAAABAAQAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAAgAAAAICAAIAAAACAAIAAgIAAAICAgADAwMAAAAD/AAD/AAAA//8A/wAAAP8A/wD//wAA////APAAAAA='));
        } elseif ($type === 'broken') {
            $this->makeFile($filename, base64_decode('broken'));
        } else {
            throw new \Exception('Unsupported file type (' . $type . ')!');
        }
    }

    /**
     * Try to find the tested addon ID
     * @return string|null
     */
    private function getTestedAddonID(): ?string
    {
        $currentDir = str_replace('\\', '/', __DIR__);
        $expectedPath = '/vendor/bearframework/addon-tests/src';
        $expectedPathLength = strlen($expectedPath);
        if (substr($currentDir, -$expectedPathLength) === $expectedPath) {
            $addonDir = substr($currentDir, 0, -$expectedPathLength);
            if (is_file($addonDir . '/autoload.php')) { // Try parse the auloload.php file and find the addon ID
                $autoloadFileContent = file_get_contents($addonDir . '/autoload.php');
                $matches = null;
                if (preg_match('/BearFramework\\\\Addons::register\([\'"]{1}(.*?)[\'"]{1}/', $autoloadFileContent, $matches)) {
                    if (isset($matches[1])) {
                        return $matches[1];
                    }
                }
            }
        }
        return null;
    }

    /**
     * A default test case so that PHPUnit will not trigger warning about no tests in the TestCase.
     * @runInSeparateProcess
     */
    public function testDefault()
    {
        $this->assertTrue(true);
    }

    /**
     * A setup method that initializes the app if not initialized.
     */
    protected function setUp()
    {
        $this->getApp(); // Initialize the app
        parent::setUp();
    }

    /**
     * Removes the temp dir created.
     */
    protected function tearDown()
    {
        foreach (self::$tempDirs as $dir) {
            $this->deleteDir($dir);
        }
        parent::tearDown();
    }

}
