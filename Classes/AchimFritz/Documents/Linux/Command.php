<?php
namespace AchimFritz\Documents\Linux;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Command {

	/**
	 * @param string $directoryName 
	 * @return void
	 */
	public function burnWavCd($directoryName) {
		$cmd = 'wodim dev=/dev/sr0 fs=4096k driveropts=burnproof -v -useinfo speed=24 -dao -eject -pad -audio ' . $directoryName . '.wav';
	}

	/**
	 * @param string $file 
	 * @return void
	 */
	public function readId3Tags($file) {
		$cmd = 'eyeD3 --no-color --rfc822 ' . $file;
		exec($cmd, $res, $ret);
		if ($ret !== 0) {
			throw new Exception('cannot execute ' . $cmd, 1418925116);
		}
		return $res;
	}
}
