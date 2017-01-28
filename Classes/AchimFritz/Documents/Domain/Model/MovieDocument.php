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
class MovieDocument extends FileSystemDocument {

	/**
	 * @var string
	 */
	protected $fileHash;

	/**
	 * @var string
	 * @ORM\Column(type="json_array")
	 */
	protected $ffmpeg;

	/**
	 * @return string
	 */
	public function getFfmpeg() {
		return $this->ffmpeg;
	}

	/**
	 * @param string $ffmpeg
	 */
	public function setFfmpeg($ffmpeg) {
		$this->ffmpeg = $ffmpeg;
	}

}
