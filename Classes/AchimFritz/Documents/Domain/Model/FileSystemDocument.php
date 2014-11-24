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
class FileSystemDocument extends Document {

	/**
	 * @var string
	 */
	protected $mountPoint = PathService::PATH_DELIMITER;

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
	 * @return string mountPoint
	 */
	public function getMountPoint() {
		return $this->mountPoint;
	}

	/**
	 * @param string $mountPoint
	 * @return void
	 */
	public function setMountPoint($mountPoint) {
		$this->mountPoint = $mountPoint;
	} 

	public function getAbsolutePath() {
		return $this->mountPoint . PathService::PATH_DELIMITER . $this->getName();
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
