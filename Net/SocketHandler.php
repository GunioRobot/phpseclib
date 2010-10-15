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

	/**
	 * Read from the socket until the first newline, or until
	 * $maxBytes bytes have been read, whichever comes first.
	 */
	function readLine($socket, $maxBytes);

	function readBytes($socket, $numBytes);

	function writeBytes($socket, $data);
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

	public function readLine($socket, $maxBytes) {
		return fgets($socket, $maxBytes);
	}

	public function readBytes($socket, $numBytes) {
		return fread($socket, $numBytes);
	}

	public function writeBytes($socket, $data) {
		return fputs($socket, $data);
	}
}