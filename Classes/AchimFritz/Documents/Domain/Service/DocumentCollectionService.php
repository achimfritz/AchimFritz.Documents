<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Facet\DocumentCollection;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentList;

/**
 * @Flow\Scope("singleton")
 */
class DocumentCollectionService {

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
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Policy\DocumentPolicy
	 */
	protected $documentPolicy;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\FilesDeleter
	 */
	protected $filesDeleter;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;


	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentCollection $documentCollection
	 * @return integer
	 */
	public function merge(DocumentCollection $documentCollection) {
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
				if ($this->documentPolicy->categoryMayBeRemoved($category, $document) === TRUE) {
					$document->removeCategory($category);
					$this->documentRepository->update($document);
					$cnt++;
				}
			}
		} 
		return $cnt;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Facet\DocumentCollection $documentCollection
	 * @return int
	 * @throws Exception
	 */
	public function removeAndDeleteFiles(DocumentCollection $documentCollection) {
		$filePaths = array();
		$documents = $documentCollection->getDocuments();
		// remove from persistenct
		try {
			foreach ($documents as $document) {
				$this->documentRepository->remove($document);
			}
			$this->documentPersistenceManager->persistAll();
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			throw new Exception('cannot remove documents from persistence', 1472713044);
		}

		// collect files
		foreach($documents as $document) {
			foreach ($document->getAdditionalFilePaths() as $filePath) {
				if (file_exists($filePath) === TRUE) {
					$filePaths[] = $filePath;
				}
			}
			$filePaths[] = $document->getAbsolutePath();
		}

		// remove FS
		try {
			$this->filesDeleter->delete($filePaths);
		} catch (\AchimFritz\Documents\Domain\FileSystem\Service\Exception $e) {
			// rollback persistence
			foreach ($documents as $document) {
				$this->documentRepository->add($document);
			}
			try {
				$this->documentPersistenceManager->persistAll();
			} catch (\AchimFritz\Documents\Persistence\Exception $e) {
				throw new Exception('cannot add documents to persistence', 1472713046);
			}
			throw new Exception('cannot remove documents from FS', 1472713047);
		}
		return count($documents);
	}

}
