<?php
namespace AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Cddb {

	const TITLE_FORMAT = 1;
	const ARTIST_TITLE_FORMAT = 2;

	/**
	 * @var int
	 */
	protected $format = self::TITLE_FORMAT;

	/**
	 * @var string
	 */
	protected $url = '';

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @return int
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * @param int $format
	 */
	public function setFormat($format) {
		$this->format = $format;
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

}
