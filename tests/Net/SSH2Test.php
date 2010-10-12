<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'Net/SSH2.php';
require_once 'Net/SocketHandler.php';

class Net_SSH2Test extends PHPUnit_Framework_TestCase {
	/**
	 * @expectedException Exception
	 *
	 * This doesn't test much, but it at least checks that we haven't
	 * made any syntax errors.
	 */
	public function testInstantiateInvalidHost() {
		$ssh = new Net_SSH2('nonexistent.invalid');
	}

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
}