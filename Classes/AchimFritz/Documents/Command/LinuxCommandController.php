<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class LinuxCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $command;

	/**
	 * burndatacd --directoryName=/mp3/tmp
	 *
	 * @param string $directoryName
	 * @return void
	 */
	public function burnDataCdCommand($directoryName) {
		try {
			$this->command->burnDataCd($directoryName);
			$this->outputLine('SUCCESS: image created');
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

}
