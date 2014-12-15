<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class FileSystemCommandController extends \TYPO3\Flow\Cli\CommandController {


	/**
	 * @access public
	 * @return void
	 */
	public function renameCommand() {
		$this->outputLine('foo');
	}

}
