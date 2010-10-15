<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'Net/SSH2.php';
require_once 'Net/SocketHandler.php';

class Net_SSH2Test extends PHPUnit_Framework_TestCase {
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Connection closed by server
	 *
	 * If we mock the socket so that it immediately and always returns
	 * EOF, then we should get an exception thrown.
	 */
	public function testImmediateEof() {
		$mockSocketHandler = $this->getMock('ISocketHandler');

		$mockSocketHandler->expects($this->any())
			->method('isEof')
			->will($this->returnValue(true));
		
		$ssh = new Net_SSH2('nonexistent.invalid', 22, 10, $mockSocketHandler);
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Cannot connect to SSH 3.0 servers
	 *
	 * Attempting to connect to a server reporting v3.0 will
	 * immediately cause an exception
	 */
	public function testConnectionV3() {
		$mockSocketHandler = $this->getMock('ISocketHandler');

		$mockSocketHandler->expects($this->any())
			->method('isEof')
			->will($this->returnValue(false));

		$mockSocketHandler->expects($this->once())
			->method('readLine')
			->will($this->returnValue('SSH-3.0'));

		$ssh = new Net_SSH2('nonexistent.invalid', 22, 10, $mockSocketHandler);
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Connection closed by server
	 *
	 * @todo This is throwing an exception, when probably there is no
	 * need to if we could get the mock expectations correct */
	public function testConnectionV2() {
		$mockSocketHandler = $this->getMock('ISocketHandler');

		$mockSocketHandler->expects($this->any())
			->method('isEof')
			->will($this->returnValue(false));

		$mockSocketHandler->expects($this->once())
			->method('readLine')
			->will($this->returnValue('SSH-2.0'));

		$mockSocketHandler->expects($this->exactly(1))
			->method('readBytes')
			->will($this->onConsecutiveCalls(pack('NC', 2, 0),
											 '00'));

		$ssh = new Net_SSH2('nonexistent.invalid', 22, 10, $mockSocketHandler);
	}
}