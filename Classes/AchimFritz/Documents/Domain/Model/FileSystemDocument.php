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
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $fileHash;

	/**
	 * @var \DateTime
	 */
	protected $mDateTime;

	/**
	 * @return string fileHash
	 */
	public function getFileHash() {
		return $this->fileHash;
	}

	/**
	 * @param string $fileHash
	 * @return void
	 */
	public function setFileHash($fileHash) {
		$this->fileHash = $fileHash;
	}

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
	 * @return string DirectoryName
	 */
	public function getDirectoryName() {
		$arr = explode(PathService::PATH_DELIMITER, $this->getName());
		$last = array_pop($arr);
		return implode(PathService::PATH_DELIMITER, $arr);
	}

	/**
	 * @return string
	 */
	public function getFileName() {
		$arr = explode(PathService::PATH_DELIMITER, $this->getName());
		$last = array_pop($arr);
		return $last;
	}
}
