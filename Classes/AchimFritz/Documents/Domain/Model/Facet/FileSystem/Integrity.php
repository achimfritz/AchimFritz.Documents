<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Integrity {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $countFileSystem;

	/**
	 * @var integer
	 */
	protected $countSolr;

	/**
	 * @param string $name 
	 * @param integer $countFileSystem 
	 * @param integer $countSolr 
	 * @return void
	 */
	public function __construct($name, $countFileSystem, $countSolr) {
		$this->name = $name;
		$this->countFileSystem = $countFileSystem;
		$this->countSolr = $countSolr;
	}

	/**
	 * @return boolean
	 */
	public function getCountDiffers() {
		if ($this->countFileSystem === $this->countSolr) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return integer
	 */
	public function getCountFileSystem() {
		return $this->countFileSystem;
	}

	/**
	 * @return integer
	 */
	public function getCountSolr() {
		return $this->countSolr;
	}

}
