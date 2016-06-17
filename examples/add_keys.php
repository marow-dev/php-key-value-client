<?php
/**
 * Adds COUNT keys with value from file long.txt
 * Run command: php add_keys.php <IP> <PORT> <COUNT>
 */
spl_autoload_register(function ($className) {
    include __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
});

$ip = $argv[1];
$port = $argv[2];
$count = (int)$argv[3] ? $argv[3] : 10;

if ( ! strlen($ip) || ! strlen($port)) {
    echo("php cmd.php <IP> <PORT>\n");
    exit;
}

echo("Connection details: IP:{$ip}, PORT:{$port}, COUNT:{$count}\n");

$c = new KVScktClient\SocketConnect($ip, $port);
$c->connect();
$cache = new KVScktClient\Commands($c);
for ($i = 0; $i <= $count - 1; $i++) {
    try {
        echo("COUNT: {$i}\n");
        $resSet = $cache->set('test' . $i, file_get_contents('long.txt'));
    } catch (Exception $e) {
        echo("ERROR: {$e->getCommand()} | {$e->getMessage()}\n");
        break;
    }
}
