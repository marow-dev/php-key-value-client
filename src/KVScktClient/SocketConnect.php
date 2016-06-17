<?php
namespace KVScktClient;

class SocketConnect {
    protected $address;
    protected $port;
    protected $socket = false;

    public function __construct($address, $port) {
        $this->address = $address;
        $this->port = $port;
    }

    public function connect() {
        $address = gethostbyname($this->address);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            throw new SocketException(socket_strerror(socket_last_error()));
        } else {
            $this->socket = $socket;
        }

        $result = socket_connect($this->socket, $this->address, $this->port);
        if ($result === false) {
            throw new SocketException(socket_strerror(socket_last_error()));
        }
    }

    public function write($text) {
        socket_write($this->socket, $text, strlen($text));
    }

    public function read() {
        $res = '';
        do {
            $data = socket_read($this->socket, 10);
            $res .= $data;
        } while (ord($data[mb_strlen($data, 'UTF-8') - 1]) != 10 && strlen($data));
        return trim($res);
    }
}
