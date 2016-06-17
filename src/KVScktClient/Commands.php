<?php
namespace KVScktClient;

class Commands {
    private $conn;
	private $commands;

    public function __construct($conn) {
        $this->conn = $conn;
		$this->createDefaultCommands();
    }

	protected function createDefaultCommands() {
		$this->addCommand('set',
            function ($arguments) {
                return array('set', $arguments[0], json_encode($arguments[1]));
            },
            function ($result) {
                return $result == 'true' ? true : false;
            }
        );
		$this->addCommand('get',
            function ($arguments) {
                return array('get', $arguments[0]);
            },
            function ($result) {
                return $result == 'undefined' ? null : json_decode($result);
            }
        );
        $this->addCommand('count',
            function ($arguments) {
                return array('count');
            },
            function ($result) {
                return $result;
            }
        );
        $this->addCommand('heapused',
            function ($arguments) {
                return array('heapused');
            },
            function ($result) {
                return $result;
            }
        );
        $this->addCommand('memsize',
            function ($arguments) {
                return array('memsize');
            },
            function ($result) {
                return $result;
            }
        );
        $this->addCommand('keys',
            function ($arguments) {
                return array('keys');
            },
            function ($result) {
                return $result;
            }
        );
	}

	public function addCommand($command, $buildFunc, $parseResultFunc) {
		$this->commands[$command] = array(
			'build'			=> $buildFunc,
			'parse_result'	=> $parseResultFunc,
		);
	}

	public function isCommand($command) {
		return isset($this->commands[$command]);
	}

	public function parseResult($name, $result) {
        $resultFunc = 'result' . ucfirst($name);
        if (method_exists($this, $resultFunc)) {
            return $this->$resultFunc($result);
        } else {
            return false;
        }
    }

    public function __call($command, $arguments) {
		if ($this->isCommand($command)) {
			$com = $this->commands[$command];
			if (is_callable($com['build'])) {
				$message = $com['build']($arguments);
			} else {
				throw new CommandException('No build function');
			}
			$this->conn->write(implode(':::', $message));
			$res = $this->conn->read();
		    if (strpos($res, 'ERROR') === 0) {
                $errorDesc = substr($res, 6);
			    throw new CommandException($errorDesc, $command);
	        } else {
				if (is_callable($com['parse_result'])) {
					return $com['parse_result']($res);
				} else {
					return $res;
				}
			}
		} else {
			throw new CommandException('Command not found');
		}
    }
}
