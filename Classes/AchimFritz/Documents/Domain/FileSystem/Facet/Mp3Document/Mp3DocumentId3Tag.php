<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document as Document;

/**
 * @Flow\Scope("prototype")
 */
class Mp3DocumentId3Tag {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Mp3Document
	 */
	protected $document;

	/**
	 * @var string
	 */
	protected $tagName;

	/**
	 * @var string
	 */
	protected $tagValue;

	/**
	 * @param string $tagName 
	 * @return void
	 */
	public function setTagName($tagName) {
		$this->tagName = $tagName;
	}

	/**
	 * @return string
	 */
	public function getTagName() {
		return $this->tagName;
	}

	/**
	 * @param string $tagValue 
	 * @return void
	 */
	public function setTagValue($tagValue) {
		$this->tagValue = $tagValue;
	}

	/**
	 * @return string
	 */
	public function getTagValue() {
		return $this->tagValue;
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\Mp3Document
	 */
	public function getDocument() {
		return $this->document;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Mp3Document $documents
	 * @return void
	 */
	public function setDocument(Document $document) {
		$this->document = $document;
	}

}
