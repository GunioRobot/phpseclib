<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'Net/SSH2.php';

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
}