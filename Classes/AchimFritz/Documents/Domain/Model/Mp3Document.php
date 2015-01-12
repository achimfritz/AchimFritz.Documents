<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Mp3Document extends FileSystemDocument {

	/**
	 * @var string
	 */
	protected $fileHash;

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $mp3DocumentConfiguration;

	/**
	 * @return \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->mp3DocumentConfiguration;
	}


}
