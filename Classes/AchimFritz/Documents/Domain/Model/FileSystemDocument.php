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
	 * @Flow\Validate(type="NotEmpty")
	 * @Flow\Identity
	 */
	protected $fileHash;

	/**
	 * @var \DateTime
	 */
	protected $mDateTime;

	/**
	 * @var \SplFileInfo
	 */
	protected $splFileInfo;

	/**
	 * @var \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

	/**
	 * @return \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->configuration;
	}

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
	 * @param \DateTime $mDateTime 
	 * @return void
	 */
	public function setMDateTime($mDateTime) {
		$this->mDateTime = $mDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getMDateTime() {
		return $this->mDateTime;
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
	 * @return string
	 */
	public function getRelativeDirectoryName() {
		return str_replace($this->getConfiguration()->getMountPoint() . PathService::PATH_DELIMITER, '', $this->getSplFileInfo()->getPathInfo()->getRealPath());
	}

	/**
	 * @return string
	 */
	public function getAbsolutePath() {
		return $this->getConfiguration()->getMountPoint() . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return string
	 */
	public function getWebPath() {
		return $this->getConfiguration()->getWebPath() . PathService::PATH_DELIMITER . $this->getName();
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

	/**
	 * @return array
	 */
	public function getAdditionalFilePaths() {
		return array();
	}
}
