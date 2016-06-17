<?php
define('IP', '127.0.0.1');
define('PORT', '8888');

spl_autoload_register(function ($className) {
    include __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
});

class ErrorsTest extends PHPUnit_Framework_TestCase {
    protected $cache;

    public function setUp() {
        $c = new KVScktClient\SocketConnect(IP, PORT);
        $c->connect();
        $this->cache = new KVScktClient\Commands($c);
    }
    public function testUnknownCommand() {
        $this->setExpectedException('Exception');
        $result = $this->cache->unknown();
        $this->expectExceptionMessage('ERROR:Command unknown');
    }

    public function testSaveArray() {
        $res = $this->cache->set('test', [1,2,3]);
        $this->assertEquals(true, $res);
        $res = $this->cache->get('test');
        $this->assertEquals([1,2,3], $res);

        $res = $this->cache->set('test2', '123');
        $this->assertEquals(true, $res);
        $res = $this->cache->get('test2');
        $this->assertEquals('123', $res);
    }
}
