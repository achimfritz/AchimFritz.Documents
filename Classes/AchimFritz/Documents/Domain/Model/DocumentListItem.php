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
class DocumentListItem {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\DocumentList
	 * @ORM\ManyToOne
	 */
	protected $documentList;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Document
	 * @ORM\ManyToOne
	 */
	protected $document;

	/**
	 * @var integer
	 */
	protected $sorting = 0;

	/**
	 * @param integer $sorting 
	 * @return void
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	/**
	 * @return integer
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * @param Document $document 
	 * @return void
	 */
	public function setDocument(Document $document) {
		$this->document = $document;
	}

	/**
	 * @return Document
	 */
	public function getDocument() {
		return $this->document;
	}
}
