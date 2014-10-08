<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class ContainerDiff {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $diff;

	/**
	 * @var integer
	 */
	protected $countFirst;

	/**
	 * @var integer
	 */
	protected $countSecond;


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
	 * @return integer
	 */
	public function getDiff() {
		return $this->diff;
	}

	/**
	 * @param integer $diff
	 * @return void
	 */
	public function setDiff($diff) {
		$this->diff = $diff;
	}

	/**
	 * @return integer
	 */
	public function getCountFirst() {
		return $this->countFirst;
	}

	/**
	 * @param integer $countFirst
	 * @return void
	 */
	public function setCountFirst($countFirst) {
		$this->countFirst = $countFirst;
	}

	/**
	 * @return integer
	 */
	public function getCountSecond() {
		return $this->countSecond;
	}

	/**
	 * @param integer $countSecond
	 * @return void
	 */
	public function setCountSecond($countSecond) {
		$this->countSecond = $countSecond;
	}

}
?>