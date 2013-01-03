<?php

namespace Leximosi;

class DutchPhoneNumber
{
	/**
	 * @var array Array containing the parts of the generated regex
	 */
	private $regEx;

	/**
	 * @param bool $nationCode Allow +31/0031
	 */
	public function __construct($nationCode = true)
	{
		$this->nationCode = true;
	}

	private function build()
	{
		// Match beginning of the string
		$this->regEx[] = '^';

		// Match nation code (31)
		// Must be preceded by `+` or `00`
		$this->regEx[] = '(.{1,2}(?<=0{2}|\+)31)?';

		// When using the nation code, the first `0` in the
		// area code gets removed or be placed in parentis.
		// Otherwise it is required.
		$this->regEx[] = '(?(?<=31)(\(0\))?|0)';

		// Build matches for the various area codes and corresponding
		// phone numbers
		$types = array(
			array(false, '{7}'),
			array('',	 '{6}'),
			array('{2}', '{5}'),
		);

		$r = '(?:';
		foreach ($types as $type)
		{
			$r .= '(?:';

			if ($type[0] === false)
			{
				$r .= '6';
			}
			else
			{
				$r .= "[1-9]{$type[0]}[0-9]";
			}

			$r .= '[\s-]?';
			$r .= "[1-9][0-9]{$type[1]}";

			$r .= ")|";
		}
		$this->regEx[] = substr($r, 0, -1) . ')';

		// Match the end of the string
		$this->regEx[] = '$';
	}

	/**
	 * Generate the regex and return it.
	 */
	public function getRegex()
	{
		$this->build();
		return implode('', $this->regEx);
	}
}
