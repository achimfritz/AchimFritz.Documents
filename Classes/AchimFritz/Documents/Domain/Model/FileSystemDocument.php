<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\FileSystemInterface;

/**
 * @Flow\Entity
 */
class FileSystemDocument extends Document {

	/**
	 * @var \DateTime
	 */
	protected $mDateTime;

	/**
	 * @var \SplFileInfo
	 */
	protected $splFileInfo;

	/**
	 * setMDateTime 
	 * 
	 * @param \DateTime $mDateTime 
	 * @return void
	 */
	public function setMDateTime($mDateTime) {
		$this->mDateTime = $mDateTime;
	}

	/**
	 * getMDateTime 
	 * 
	 * @return \DateTime
	 */
	public function getMDateTime() {
		return $this->mDateTime;
	}

	/**
	 * @return void
	 */
	public function getMountPoint() {
		return FileSystemInterface::MOUNT_POINT;
	}

	/**
	 * @return string
	 */
	public function getAbsolutePath() {
		return $this->getMountPoint() . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return string
	 */
	public function getFileName() {
		return $this->getSplFileInfo()->getBasename();
	}

	/**
	 * @return string
	 */
	public function getDirectoryName() {
		return $this->getSplFileInfo()->getPathInfo()->getBasename();
	}

	/**
	 * getSplFileInfo 
	 * 
	 * @return \SplFileInfo
	 */
	public function getSplFileInfo() {
		if (!$this->splFileInfo instanceof \SplFileInfo) {
			$this->splFileInfo = new \SplFileInfo($this->getAbsolutePath());
		}
		return $this->splFileInfo;
	}

}
?>
