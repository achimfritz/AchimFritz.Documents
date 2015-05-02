<?php
namespace AchimFritz\Documents\Surf\Application\Image;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents\Surf".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

abstract class AbstractApplication  extends \TYPO3\Surf\Domain\Model\Application {

	/**
	 * The mount point
	 * @var string
	 */
	protected $mountPoint;

	/**
	 * The orig path
	 * @var string
	 */
	protected $origPath;

	/**
	 * The main path
	 * @var string
	 */
	protected $mainPath;

	/**
	 * The admin path
	 * @var string
	 */
	protected $adminPath;
	
	/**
	 * @var string
	 */
	protected $target;
	
	/**
	 * @var boolean
	 */
	protected $isExif;
	
	/**
	 * Constructor
	 *
	 * @param string $name
	 */
	public function __construct($name = 'Image') {
		parent::__construct($name);
	}

	/**
	 * Get the Image's mount point
	 *
	 * @return string The Image's mount point
	 */
	public function getMountPoint() {
		return $this->mountPoint;
	}

	/**
	 * Sets this Image's mount point
	 *
	 * @param string $mountPoint The Image's mount point
	 * @return void
	 */
	public function setMountPoint($mountPoint) {
		$this->mountPoint = $mountPoint;
	}

	/**
	 * Get the Image's orig path
	 *
	 * @return string The Image's orig path
	 */
	public function getOrigPath() {
		return $this->origPath;
	}

	/**
	 * Sets this Image's orig path
	 *
	 * @param string $origPath The Image's orig path
	 * @return void
	 */
	public function setOrigPath($origPath) {
		$this->origPath = $origPath;
	}

	/**
	 * Get the Image's main path
	 *
	 * @return string The Image's main path
	 */
	public function getMainPath() {
		return $this->mainPath;
	}

	/**
	 * Sets this Image's main path
	 *
	 * @param string $mainPath The Image's main path
	 * @return void
	 */
	public function setMainPath($mainPath) {
		$this->mainPath = $mainPath;
	}

	/**
	 * Get the Image's admin path
	 *
	 * @return string The Image's admin path
	 */
	public function getAdminPath() {
		return $this->adminPath;
	}

	/**
	 * Sets this Image's admin path
	 *
	 * @param string $adminPath The Image's admin path
	 * @return void
	 */
	public function setAdminPath($adminPath) {
		$this->adminPath = $adminPath;
	}
	
	/**
	 * getTarget
	 *
	 * @return string target
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * setTarget
	 *
	 * @param string $target
	 * @return void
	 */
	public function setTarget($target) {
		$this->target = $target;
	}
	
	/**
	 * getIsExif
	 *
	 * @return boolean isExif
	 */
	public function getIsExif() {
		return $this->isExif;
	}

	/**
	 * setIsExif
	 *
	 * @param boolean $isExif
	 * @return void
	 */
	public function setIsExif($isExif) {
		$this->isExif = $isExif;
	}

}
?>
