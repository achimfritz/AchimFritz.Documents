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
	 * @var \DateTime
	 */
	protected $mDateTime;

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

}
