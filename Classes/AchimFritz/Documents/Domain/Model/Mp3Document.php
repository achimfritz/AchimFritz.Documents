<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use AchimFritz\Documents\Domain\FileSystemInterface;

/**
 * @Flow\Entity
 */
class Mp3Document extends FileSystemDocument {

	/**
	 * @return void
	 */
	public function getMountPoint() {
		return FileSystemInterface::MP3_MOUNT_POINT;
	}

	/**
	 * @return string webPath
	 */
	public function getWebPath() {
		return FileSystemInterface::MP3_WEB . PathService::PATH_DELIMITER . $this->getName();
	}

}
