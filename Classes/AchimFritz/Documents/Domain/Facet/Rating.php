<?php
namespace AchimFritz\Documents\Domain\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Rating {

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $value;

	/**
	 * @var integer
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $rate;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return void
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @return integer
	 */
	public function getRate() {
		return $this->rate;
	}

	/**
	 * @param integer $rate
	 * @return void
	 */
	public function setRate($rate) {
		$this->rate = $rate;
	}

}
