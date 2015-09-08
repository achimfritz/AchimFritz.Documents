<?php
namespace AchimFritz\Documents\Domain\Model\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class TvRecording {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\TvChannel
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $tvChannel;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $starttime;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $title;

	/**
	 * @var integer
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $length;


	/**
	 * @return tvChannel
	 */
	public function getTvChannel() {
		return $this->tvChannel;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\TvChannel $tvChannel
	 * @return void
	 */
	public function setTvChannel($tvChannel) {
		$this->tvChannel = $tvChannel;
	}

	/**
	 * @return string
	 */
	public function getStarttime() {
		return $this->starttime;
	}

	/**
	 * @param string $starttime
	 * @return void
	 */
	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return integer
	 */
	public function getLength() {
		return $this->length;
	}

	/**
	 * @param integer $length
	 * @return void
	 */
	public function setLength($length) {
		$this->length = $length;
	}

}
