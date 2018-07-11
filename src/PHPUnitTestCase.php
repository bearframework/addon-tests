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

    /**
     * 
     * @param array $config
     * @return void
     * @throws \Exception
     */
    protected function initializeApp(array $config = []): void
    {
        if (self::$app !== null) {
            throw new \Exception('The app is already initialized!');
        }
        $dir = $this->getTempDir() . '/';
        self::$app = new \BearFramework\App();
        $this->makeDir($dir . 'app/');
        $this->makeDir($dir . 'data/');
        $this->makeDir($dir . 'logs/');
        self::$app->config->handleErrors = false;

        $initialConfig = [
            'appDir' => $dir . 'app/',
            'dataDir' => $dir . 'data/',
            'logsDir' => $dir . 'logs/',
            'handleErrors' => false
        ];
        $config = array_merge($initialConfig, $config);
        foreach ($config as $key => $value) {
            if ($key === 'addonOptions') {
                continue;
            }
            self::$app->config->$key = $value;
        }

        self::$app->initialize();
        self::$app->request->base = 'http://example.com/';
        self::$app->request->method = 'GET';

        $list = \BearFramework\Addons::getList();
        if (isset($list[0])) {
            self::$app->addons->add($list[0]->id, isset($config['addonOptions']) ? $config['addonOptions'] : []);
        }
    }

    /**
     * 
     * @return \BearFramework\App
     */
    protected function getApp(): \BearFramework\App
    {
        if (self::$app === null) {
            $this->initializeApp();
        }
        return self::$app;
    }

    /**
     * 
     * @return string
     */
    protected function getTempDir(): string
    {
        $dir = sys_get_temp_dir() . '/bearframework-addon-unittests/' . uniqid();
        $this->makeDir($dir);
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
     * A setup method that initializes the app if not initialized. You can override it and initialize the app yourself.
     */
    protected function setUp()
    {
        if (self::$app === null) {
            $this->initializeApp();
        }
        parent::setUp();
    }

    /**
     * A default test case so that PHPUnit will not trigger warning about no tests in the TestCase.
     * @runInSeparateProcess
     */
    public function testDefault()
    {
        $this->assertTrue(true);
    }

}
