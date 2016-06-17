<?php
error_reporting(E_ALL & ~E_NOTICE);
spl_autoload_register(function ($className) {
    include __DIR__ . '/../src/' . str_replace('\\', '/', $className) . '.php';
});

$ip = $argv[1];
$port = $argv[2];

if ( ! strlen($ip) || ! strlen($port)) {
    echo("php cmd.php <IP> <PORT>\n");
    exit;
}

function displayOptions() {
    echo("1. Memory size occupied by key=>values\n");
    echo("2. Keys count\n");
    echo("3. Heap size\n");
    echo("4. Show all keys\n");
    echo("9. Display options\n");
    echo("0. Exit\n");
    echo("Enter option: ");
}

function connect() {
    global $ip, $port;
    $commands = null;
    try {
        $c = new KVScktClient\SocketConnect($ip, $port);
        $c->connect();
        $commands = new KVScktClient\Commands($c);
    } catch (KVScktClient\SocketException $e) {
        echo("EXCEPTION: " . $e->getMessage() . "\n");
    }
    return $commands;
}

function memorySize($commands) {
    $res = $commands->memsize();
    echo("COMMAND: memsize\n");
    echo("RESULT: {$res}\n");
    echo("Enter option: ");
}

function keysCount($commands) {
    $res = $commands->count();
    echo("COMMAND: count\n");
    echo("RESULT: {$res}\n");
    echo("Enter option: ");
}

function heapUsed($commands) {
    $res = $commands->heapused();
    echo("COMMAND: heapused\n");
    echo("RESULT: {$res}\n");
    echo("Enter option: ");
}

function keys($commands) {
    $keys = @json_decode($commands->keys());
    echo("COMMAND: keys\n");
    if (is_string($keys)) {
        $res = $keys;
    } else {
        if ( ! count($keys)) {
            $res = 'NO KEYS';
        } else {
            $res = implode(', ', $keys);
        }
    }
    echo("RESULT: {$res}\n");
    echo("Enter option: ");
}

function chooseOption($opt) {
    $opt = (int)$opt;
    if ($opt >= 1 && $opt <= 4) {
        $commands = connect();
        if ($commands) {
            if ($opt === 1) {
                memorySize($commands);
            } elseif ($opt === 2) {
                keysCount($commands);
            } elseif ($opt === 3) {
                heapUsed($commands);
            } elseif ($opt === 4) {
                keys($commands);
            }
        } else {
            displayOptions();
        }
    }
    if ($opt === 9) {
        displayOptions();
    } elseif ($opt === 0) {
        echo("Exiting\n");
        exit;
    }
}

displayOptions();
$handle = fopen ("php://stdin","r");
while (true) {
    $line = fgets($handle);
    chooseOption($line);
}
