<?php
namespace AchimFritz\Documents\Domain\Model\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Scope("prototype")
 */
class RenameCategory {

	/**
	 * @var string
	 */
	protected $oldPath;

	/**
	 * @var string
	 */
	protected $newPath;


	/**
	 * @return string
	 */
	public function getOldPath() {
		return $this->oldPath;
	}

	/**
	 * @param string $oldPath
	 * @return void
	 */
	public function setOldPath($oldPath) {
		$this->oldPath = $oldPath;
	}

	/**
	 * @return string
	 */
	public function getNewPath() {
		return $this->newPath;
	}

	/**
	 * @param string $newPath
	 * @return void
	 */
	public function setNewPath($newPath) {
		$this->newPath = $newPath;
	}

}
