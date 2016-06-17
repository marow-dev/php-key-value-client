# php-key-value-client
Nodejs-key-value-server php client

This is example client that uses [nodejs-key-value-server](https://github.com/marow-dev/nodejs-key-value-server) written in PHP.

Connection
Connection is make using KVScktClient\SocketConnect class.

```php
$c = new KVScktClient\SocketConnect(8888, '127.0.0.1');
$c->connect();
```

Commands are sent using KVScktClient\Commands class.
Commands class builds command and sends using object of class KVScktClient\SocketConnect.
Methods provided by KVScktClient\Commands have the same names as server commmands names.

Example of **memsize** command
```php
try {
	$c = new KVScktClient\SocketConnect($ip, $port);
	$c->connect();
	$commands = new KVScktClient\Commands($c);
	$commands->memsize();
} catch (KVScktClient\SocketException $e) {
	echo("EXCEPTION: " . $e->getMessage() . "\n");
}
```
Example of **set** command
```php
try {
	$c = new KVScktClient\SocketConnect($ip, $port);
	$c->connect();
	$commands = new KVScktClient\Commands($c);
	$commands->set('test key', 'test value');
} catch (KVScktClient\SocketException $e) {
	echo("EXCEPTION: " . $e->getMessage() . "\n");
}
```
