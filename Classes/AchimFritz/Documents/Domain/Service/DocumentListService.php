<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentCollection;
use AchimFritz\Documents\Domain\Model\DocumentList;
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
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 */
	public function merge(DocumentList $documentList) {
		$items = $documentList->getDocumentListItems();
		$documentList = $this->documentListRepository->getPersistedOrAdd($documentList);
		foreach ($items AS $item) {
			if ($documentList->hasDocumentListItem($item) === FALSE) {
				// add item
				$documentList->addDocumentListItem($item);
				// TODO WHY ???
				$item->setDocumentList($documentList);
			} else {
				// update sorting ... TODO should be done autom. by persistence
				//$documentList->getDocumentItem($item)->setSorting($item->getSorting());
			}
			// add document to category
			$document = $item->getDocument();
			$category = $documentList->getCategory();
			// TODO Repo + DocumentCollectionService
			if ($document->hasCategory($category) === FALSE) {
				$document->addCategory($category);
				$this->documentRepository->update($document);
			}
		}
		$this->documentListRepository->update($documentList);
		return $documentList;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return integer
	 */
	public function remove(DocumentList $documentList) {
		$cnt = 0;
		return $cnt;
	}

}
