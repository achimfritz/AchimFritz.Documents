<?php
namespace AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Integrity extends \AchimFritz\Documents\Domain\FileSystem\Facet\Integrity {

	/**
	 * @var array
	 */
	protected $thumbs = array();

	/**
	 * @var boolean
	 */
	protected $timestampsAreInitialized = FALSE;

	/**
	 * @var boolean
	 */
	protected $imageIsRotated = FALSE;

	/**
	 * @var boolean
	 */
	protected $geeqieMetadataExists = FALSE;

	/**
	 * @var boolean
	 */
	protected $isExif = FALSE;

	/**
	 * @return array thumbs
	 */
	public function getThumbs() {
		return $this->thumbs;
	}

	/**
	 * @param array $thumbs
	 * @return void
	 */
	public function setThumbs($thumbs) {
		$this->thumbs = $thumbs;
	}

	/**
	 * @return boolean timestampsAreInitialized
	 */
	public function getTimestampsAreInitialized() {
		return $this->timestampsAreInitialized;
	}

	/**
	 * @param boolean $timestampsAreInitialized
	 * @return void
	 */
	public function setTimestampsAreInitialized($timestampsAreInitialized) {
		$this->timestampsAreInitialized = $timestampsAreInitialized;
	}

	/**
	 * @return boolean imageIsRotated
	 */
	public function getImageIsRotated() {
		return $this->imageIsRotated;
	}

	/**
	 * @param boolean $imageIsRotated
	 * @return void
	 */
	public function setImageIsRotated($imageIsRotated) {
		$this->imageIsRotated = $imageIsRotated;
	} 

	/**
	 * @return boolean geeqieMetadataExists
	 */
	public function getGeeqieMetadataExists() {
		return $this->geeqieMetadataExists;
	}

	/**
	 * @param boolean $geeqieMetadataExists
	 * @return void
	 */
	public function setGeeqieMetadataExists($geeqieMetadataExists) {
		$this->geeqieMetadataExists = $geeqieMetadataExists;
	}

	/**
	 * @return boolean isExif
	 */
	public function getIsExif() {
		return $this->isExif;
	}

	/**
	 * @param boolean $isExif
	 * @return void
	 */
	public function setIsExif($isExif) {
		$this->isExif = $isExif;
	}

	/**
	 * @return boolean readyForRotation
	 */
	public function getReadyForRotation() {
		if ($this->getTimestampsAreInitialized() === TRUE &&
			($this->getGeeqieMetadataExists() === TRUE || $this->getIsExif() === TRUE)
		) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @return string
	 */
	public function getNextStep() {
		if ($this->getTimestampsAreInitialized() === FALSE) {
			return 'init';
		} elseif (count($this->getThumbs()) === $this->getCountFileSystem() && $this->getCountFileSystem() !== $this->getCountSolr()) {
			return 'index';
		} elseif ($this->getReadyForRotation() === TRUE && count($this->getThumbs()) === $this->getCountFileSystem()) {
			return 'solr';
		} elseif ($this->getImageIsRotated() === TRUE && count($this->getThumbs()) !== $this->getCountFileSystem()) {
			return 'thumb';
		} elseif ($this->getReadyForRotation() === TRUE && count($this->getThumbs()) !== $this->getCountFileSystem()) {
			return 'rotateandthumb';
		} else {
			return '';
		}
	}

	/**
	 * @return boolean readyForThumbs
	 */
	public function getReadyForThumbs() {
		if ($this->getReadyForRotation() === TRUE AND $this->getImageIsRotated() === TRUE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}
