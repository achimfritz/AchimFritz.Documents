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
	protected $endtime;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $title;


	/**
	 * @var bool
	 */
	protected $shutdownAfter = false;


	/**
	 * @return \AchimFritz\Documents\Domain\Model\TvChannel
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
	 * @return string
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * @param string $endtime
	 */
	public function setEndtime($endtime)  {
		$this->endtime = $endtime;
	}

	/**
	 * @return boolean
	 */
	public function getShutdownAfter() {
		return $this->shutdownAfter;
	}

	/**
	 * @param boolean $shutdownAfter
	 */
	public function setShutdownAfter($shutdownAfter) {
		$this->shutdownAfter = $shutdownAfter;
	}

	/**
	 * @return integer
	 */
	public function getLength() {
		list($starttimeH, $starttimeM) = explode(':', $this->getStarttime());
		list($endtimeH, $endtimeM) = explode(':', $this->getEndtime());
		if ((int)$starttimeH > (int)$endtimeH) {
			$hDiff = ((((int)$endtimeH + 24) - (int)$starttimeH) * 60);
		} else {
			$hDiff = (((int)$endtimeH - (int)$starttimeH) * 60);
		}
		$mDiff = (int)$endtimeM - (int)$starttimeM;
		return $hDiff + $mDiff;
	}

}
