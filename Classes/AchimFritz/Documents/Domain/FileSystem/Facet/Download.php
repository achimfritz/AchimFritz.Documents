<?php
namespace AchimFritz\Documents\Domain\FileSystem\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Download {

	/**
	 * @var string
	 */
	protected $target = '';

	/**
	 * @var string
	 */
	protected $url = '';

	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * @return string
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @param string $target
	 */
	public function setTarget($target) {
		$this->target = $target;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}


}
