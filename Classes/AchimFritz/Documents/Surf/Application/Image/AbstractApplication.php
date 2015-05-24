<?php
namespace AchimFritz\Documents\Surf\Application\Image;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents\Surf".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

abstract class AbstractApplication  extends \TYPO3\Surf\Domain\Model\Application {

	/**
	 * @var string
	 */
	protected $target;
	
	/**
	 * Constructor
	 *
	 * @param string $name
	 */
	public function __construct($name = 'Image') {
		parent::__construct($name);
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

}
