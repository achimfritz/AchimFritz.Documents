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
class DocumentList {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Category
	 * @ORM\OneToOne
	 */
	protected $category;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\DocumentListItem>
	 * @ORM\OneToMany(mappedBy="documentList", cascade={"all"})
	 * @ORM\OrderBy({"sorting" = "ASC"})
	 */
	protected $documentListItems;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->documentListItems = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\DocumentListItem>
	 */
	public function getDocumentListItems() {
		return $this->documentListItems;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\DocumentListItem> $documentListItems
	 * @return void
	 */
	public function setDocumentListItems(\Doctrine\Common\Collections\Collection $documentListItems) {
		$this->documentListItems = $documentListItems;
	}

	/**
	 * @param DocumentListItem $documentListItems
	 * @return void
	 */
	public function addDocumentListItem(DocumentListItem $documentListItem) {
		$documentListItem->setDocumentList($this);
		$this->documentListItems->add($documentListItem);
	}

	/**
	 * @param DocumentListItem $documentListItems
	 * @return boolean
	 */
	public function hasDocumentListItem(DocumentListItem $documentListItem) {
		return $this->documentListItems->contains($documentListItem);
	}

	/**
	 * @param Document $document 
	 * @return boolean
	 */
	public function hasDocument(Document $document) {
		$items = $this->getDocumentListItems();
		foreach ($items AS $item) {
			if ($item->getDocument() === $document) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * @param DocumentListItem $documentListItem 
	 * @return void
	 */
	public function updateDocumentListItem(DocumentListItem $documentListItem) {
		$items = $this->getDocumentListItems();
		foreach ($items AS $item) {
			if ($item->getDocument() === $documentListItem->getDocument()) {
				$item->setSorting($documentListItem->getSorting());
			}
		}
	}

	/**
	 * @param DocumentListItem $documentListItems
	 * @return boolean
	 */
	public function removeDocumentListItem(DocumentListItem $documentListItem) {
		$this->documentListItems->removeElement($documentListItem);
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
