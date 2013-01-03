<?php
use \Leximosi\DutchPhoneNumber;

require_once __DIR__ . '/../lib/Leximosi/DutchPhoneNumber/DutchPhoneNumber.php';

class DutchPhoneNumberTest extends PHPUnit_Framework_TestCase
{
	/** @var DutchPhoneNumber */
	private $regEx;

	protected function setUp()
	{
		$this->regEx = new DutchPhoneNumber();
	}

	public function matchTestData()
	{
		return array(
			// 3 digit area code
			array('0201345679'),
			array('020-1345679'),
			array('020 1345679'),
			array('0031201345679'),
			array('003120-1345679'),
			array('003120 1345679'),
			array('+31201345679'),
			array('+3120-1345679'),
			array('+3120 1345679'),

			// 4 digit area code
			array('0116345679'),
			array('0116-345679'),
			array('0116 345679'),
			array('0031116345679'),
			array('0031116-345679'),
			array('0031116 345679'),
			array('+31116345679'),
			array('+31116-345679'),
			array('+31116 345679'),

			// Mobile number
			array('0612345789'),
			array('06-12345789'),
			array('06 12345789'),
			array('0031612345789'),
			array('00316-12345789'),
			array('00316 12345789'),
			array('+31612345789'),
			array('+316-12345789'),
			array('+316 12345789'),

			// Nation code with `(0)`
			array('0031(0)20 1345679'),
			array('+31(0)6-12345789'),
		);
	}

	/**
	 * @dataProvider matchTestData
	 */
	public function testNumbers($number)
	{
		$this->assertRegExp("#" . $this->regEx->getRegex() . "#", $number);
	}

	public function missMatchTestData()
	{
		return array(
			// To long 3 digit area code
			array('02013456790'),
			array('020-13456790'),
			array('020 13456790'),

			// To long 3 digit area code
			array('02213456790'),
			array('0221-3456790'),
			array('0221 3456790'),

			// To short
			array('020134567'),
			array('020-134567'),
			array('020 134567'),

			// Malformed nation code
			array('+3106-12345789'),
			array('+326-12345789'),

			// Malformed area code
			array('001-1234567'),
			array('100-1234567'),
			array('0101245983'),	// @todo

			// Number starting with a `0`
			array('0200345679'),
			array('0116045679'),
			array('0602345789'),
		);
	}

	/**
	 * @dataProvider missMatchTestData
	 */
	public function testFailNumbers($number)
	{
		$this->assertNotRegExp("#" . $this->regEx->getRegex() . "#", $number);
	}
}
