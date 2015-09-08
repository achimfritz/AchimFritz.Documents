<?php
namespace AchimFritz\Documents\Domain\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;

/**
 * @Flow\Scope("prototype")
 */
class DocumentCollection {

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document>
	 */
	protected $documents;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Category
	 */
	protected $category;

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
	 * @return \AchimFritz\Documents\Domain\Model\Category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function setCategory(\AchimFritz\Documents\Domain\Model\Category $category) {
		$this->category = $category;
	}

}
