<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\DocumentListItem;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class DocumentListService {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\FileSystemDocumentRepository
	 */
	protected $fileSystemDocumentRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 */
	public function merge(DocumentList $documentList) {
		$items = $documentList->getDocumentListItems();
		$persistedDocumentList = $this->documentListRepository->getPersistedOrAdd($documentList);
		foreach ($items AS $item) {
			if ($persistedDocumentList->hasDocument($item->getDocument()) === FALSE) {
				$persistedDocumentList->addDocumentListItem($item);
				// add document to category
				$document = $item->getDocument();
				$category = $persistedDocumentList->getCategory();
				if ($document->hasCategory($category) === FALSE) {
					$document->addCategory($category);
					$this->documentRepository->update($document);
				}
			} else {
				$persistedDocumentList->updateDocumentListItem($item);
			}
		}
		$this->documentListRepository->update($persistedDocumentList);
		return $persistedDocumentList;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @throws Exception
	 */
	public function remove(DocumentList $documentList) {
		$category = $this->categoryRepository->findOneByPath($documentList->getCategory()->getPath());
		// already persisted?
		$persistedDocumentList = $this->documentListRepository->findOneByCategory($category);
		if ($persistedDocumentList instanceof DocumentList) {
			foreach ($documentList->getDocumentListItems() AS $item) {
				foreach ($persistedDocumentList->getDocumentListItems() AS $persistedItem) {
					if ($persistedItem->getDocument() === $item->getDocument()) {
						$persistedDocumentList->removeDocumentListItem($persistedItem);
						// remove category
						$document = $item->getDocument();
						$document->removeCategory($category);
						$this->documentRepository->update($document);
					}
				}
			}
		} else {
			throw new Exception('documentList not found ' . $documentList->getCategory()->getPath(), 1420201144);
		}
		$this->documentListRepository->update($persistedDocumentList);
		return $persistedDocumentList;
	}
	

}
