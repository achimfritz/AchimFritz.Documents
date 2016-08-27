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
	 * @param \AchimFritz\Documents\Domain\Model\DocumentCollection $documentCollection
	 * @return void
	 * @throws Exception
	 */
	public function removeAndDeleteFiles(DocumentCollection $documentCollection) {
		// TODO check documentList ...
		// TOOD solr reload
		foreach($documentCollection->getDocuments() as $document) {
			foreach ($document->getAdditionalFilePaths() as $filePath) {
				if (file_exists($filePath) === TRUE && @unlink($filePath) === FALSE) {
					throw new Exception ('cannot remove ' . $filePath, 1468510840);
				}
			}
			if (@unlink($document->getAbsolutePath()) === FALSE) {
				throw new Exception ('cannot remove ' . $document->getAbsolutePath(), 1468510841);
			}
			$this->documentRepository->remove($document);
		}
	}

}
