<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class FileSystemProperty {

	const EXIF_KEY_VALUE_DELIMITER = "\t";
	const EXIF_ORIENTATION_KEY = 'Orientation';
	const EXIF_WIDTH_KEY = 'Pixel X Dimension';
	const EXIF_HEIGHT_KEY = 'Pixel Y Dimension';
	const ORIENTATION_NONE = 0;
	const ORIENTATION_0 = 1;
	const EXIF_ORIENTATION_0 = 'Top-left';
	const EXIF_ORIENTATION_90 = 'Right-top';
	const EXIF_ORIENTATION_180 = 'Bottom-right';
	const EXIF_ORIENTATION_270 = 'Left-bottom';

/*
1	top	left side
2	top	right side
3	bottom	right side
4	bottom	left side
5	left side	top
6	right side	top
7	right side	bottom
8	left side	bottom
*/




	/**
	 * @var array
	 */
	protected $exifData = array();

	/**
	 * @var string
	 */
	protected $absolutePath = '';

	/**
	 * @var string
	 */
	protected $timestamp = '';

	/**
	 * @var array
	 */
	protected $geeqieOrientation = self::ORIENTATION_NONE;

	/**
	 * @var integer
	 */
	protected $width;

	/**
	 * @var integer
	 */
	protected $height;

	/**
	 * @return array geeqieOrientation
	 */
	public function getGeeqieOrientation() {
		return $this->geeqieOrientation;
	}

	/**
	 * @param array $geeqieOrientation
	 * @return void
	 */
	public function setGeeqieOrientation($geeqieOrientation) {
		$this->geeqieOrientation = $geeqieOrientation;
	}


	/**
	 * @return array exifData
	 */
	public function getExifData() {
		return $this->exifData;
	}

	/**
	 * @param array $exifData
	 * @return void
	 */
	public function setExifData($exifData) {
		$this->exifData = $exifData;
	}

	/**
	 * @return string
	 */
	public function getExifOrientation() {
		return $this->getExifValue(self::EXIF_ORIENTATION_KEY);
	}

	/**
	 * @return string
	 */
	public function getExifWidth() {
		return $this->getExifValue(self::EXIF_WIDTH_KEY);
	}

	/**
	 * @return string
	 */
	public function getExifHeight() {
		return $this->getExifValue(self::EXIF_HEIGHT_KEY);
	}

	/**
	 * @param string $key 
	 * @return string
	 */
	public function getExifValue($key) {
		foreach ($this->getExifData() as $line) {
			$arr = explode(self::EXIF_KEY_VALUE_DELIMITER, $line);
			if (count($arr) === 2) {
				list($currentKey, $val) = $arr;
				if (trim($currentKey) === $key) {
					return trim($val);
				}
			}
		}
		return '';
	}

	/**
	 * @return boolean
	 */
	public function hasExifData() {
		if (count($this->getExifData()) > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @return integer height
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @param integer $height
	 * @return void
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * @return integer width
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param integer $width
	 * @return void
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * @return string timestamp
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @param string $timestamp
	 * @return void
	 */
	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @return string absolutePath
	 */
	public function getAbsolutePath() {
		return $this->absolutePath;
	}

	/**
	 * @param string $absolutePath
	 * @return void
	 */
	public function setAbsolutePath($absolutePath) {
		$this->absolutePath = $absolutePath;
	} 


}
