<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Entity
 */
class ImageDocument extends FileSystemDocument {


	/**
	 * @return integer
	 */
	public function getYear() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[0];
	}

	/**
	 * @return integer
	 */
	public function getMonth() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[1];
	}

	/**
	 * @return integer
	 */
	public function getDay() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[2];
	}
}
