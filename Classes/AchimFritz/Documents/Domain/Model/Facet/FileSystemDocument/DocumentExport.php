<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;

/**
 * @Flow\Scope("prototype")
 */
class DocumentExport {

	const SORT_BY_PREFIX_NONE = 0;
	const SORT_BY_PREFIX_TIMESTAMP = 1;
	const SORT_BY_PREFIX_COUNTER = 2;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document>
	 */
	protected $documents;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var boolean
	 */
	protected $useThumb = FALSE;

	/**
	 * @var boolean
	 */
	protected $useFullPath = FALSE;

	/**
	 * @var integer
	 */
	protected $sortByPrefix = self::SORT_BY_PREFIX_NONE;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->documents = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document
	 */
	public function getDocuments() {
		return $this->documents;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document $documents
	 * @return void
	 */
	public function setDocuments(\Doctrine\Common\Collections\Collection $documents) {
		$this->documents = $documents;
	}

	/**
	 * @param Document $document 
	 * @return void
	 */
	public function addDocument(Document $document) {
		$this->documents->add($document);
	}


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
	 * @return boolean
	 */
	public function getUseThumb() {
		return $this->useThumb;
	}

	/**
	 * @param boolean $useThumb
	 * @return void
	 */
	public function setUseThumb($useThumb) {
		$this->useThumb = $useThumb;
	}

	/**
	 * @return boolean
	 */
	public function getUseFullPath() {
		return $this->useFullPath;
	}

	/**
	 * @param boolean $useFullPath
	 * @return void
	 */
	public function setUseFullPath($useFullPath) {
		$this->useFullPath = $useFullPath;
	}

	/**
	 * @return integer
	 */
	public function getSortByPrefix() {
		return $this->sortByPrefix;
	}

	/**
	 * @param integer $sortByPrefix
	 * @return void
	 */
	public function setSortByPrefix($sortByPrefix) {
		$this->sortByPrefix = $sortByPrefix;
	}

}
