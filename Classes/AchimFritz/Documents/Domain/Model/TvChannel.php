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
class TvChannel {

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $channel;


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
	public function getDecodedChannel() {
		return str_replace('(', '\(', str_replace(')', '\)', $this->channel));
	}

	/**
	 * @return string
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * @param string $channel
	 * @return void
	 */
	public function setChannel($channel) {
		$this->channel = $channel;
	}

}
