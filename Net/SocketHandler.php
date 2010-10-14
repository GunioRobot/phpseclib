<?php

/**
 * Interface to an object that will handle all the details of socket
 * communications for Net_SSHx and related classes. This is so that we
 * can stub out all the over-the-wire stuff for unit testing.
 */
interface ISocketHandler {
	/**
	 * MUST return a valid socket, or else throw an appropriate
	 * exception
	 */
	function openSocket($host, $port, $timeout);

	function isEof($socket);

	function readBytes($socket, $numBytes);
}

class DefaultSocketHandler implements ISocketHandler {
	public function openSocket($host, $port, $timeout) {
		$fsock = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$fsock) {
            throw new Exception("Cannot connect to $host. Error $errno. $errstr");
        }

		return $fsock;
	}

	public function isEof($socket) {
		return feof($socket);
	}

	public function readBytes($socket, $numBytes) {
		return fgets($socket, $numBytes);
	}
}