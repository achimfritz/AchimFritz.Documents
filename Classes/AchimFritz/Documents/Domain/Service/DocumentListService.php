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
	 * @return integer
	 */
	public function merge(DocumentList $documentList) {
		$cnt = 0;
		$category = $documentCollection->getCategory();
		$category = $this->categoryRepository->getPersistedOrAdd($category);
		$documents = $documentCollection->getDocuments();
		foreach ($documents AS $document) {
			if ($document->hasCategory($category) === FALSE) {
				$document->addCategory($category);
				$this->documentRepository->update($document);
				$cnt ++;
			}
		}
		return $cnt;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentCollection $documentCollection
	 * @return integer
	 */
	public function remove(DocumentCollection $documentCollection) {
		$category = $documentCollection->getCategory();
		$cnt = 0;
		// already persisted?
		$persistedCategory = $this->categoryRepository->findOneByPath($category->getPath());
		if ($persistedCategory instanceof Category) {
			$category = $persistedCategory;
			$documents = $documentCollection->getDocuments();
			foreach ($documents AS $document) {
				if ($document->hasCategory($category) === TRUE) {
					$document->removeCategory($category);
					$this->documentRepository->update($document);
					$cnt++;
				}
			}
		} 
		return $cnt;
	}

}
