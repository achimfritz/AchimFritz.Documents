<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;

/**
 * @Flow\Scope("singleton")
 */
class RatingCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\DocumentCollectionService
	 */
	protected $documentCollectionService;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Solr\Helper
	 * @Flow\Inject
	 */
	protected $solrHelper;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 * @Flow\Inject
	 */
	protected $categoryRepository;

	/**
	 * @param string $name 
	 * @param string $value 
	 * @return \AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;
	 */
	protected function getDocumentCollection($name, $value) {
		switch ($name) {
			case 'title':
				$documents = $this->documentRepository->findByName($value);
				break;
			case 'album':
			case 'artist':
				$fq = $name . ':' . $value;
				$docs = $this->solrHelper->findDocumentsByFq($fq);
				$documents = $this->documentRepository->findByNames($docs);
				break;
			default:
				$documents = array();
				break;
		}
		$documentCollection = new DocumentCollection();
		foreach ($documents as $document) {
			$documentCollection->addDocument($document);
		}
		return $documentCollection;
	}

	/**
	 * @param string $name 
	 * @param string $value 
	 * @return \AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;
	 */
	protected function deleteRatings($name, $value) {
		$documentCollection = $this->getDocumentCollection($name, $value);
      $categories = $this->categoryRepository->findByPathHead('rating/' . $name);
      foreach($categories as $category) {
			$documentCollection->setCategory($category);
			$this->documentCollectionService->remove($documentCollection);
      }
		return $documentCollection;
	}

	/**
	 * @param string $name 
	 * @param int $rating
	 * @param \AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;
	 * @return \AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;
	 */
	protected function createRatings($name, $rating, DocumentCollection $documentCollection) {
      $category = new Category();
      $category->setPath('rating/' . $name . '/' . (string)$rating);
      $documentCollection->setCategory($category);
		$this->documentCollectionService->merge($documentCollection);
		return $documentCollection;
	}

	/**
	 * @param string $name 
	 * @param string $value 
	 * @return void
	 */
	public function deleteCommand($name='artist', $value='ACDC') {
		$documentCollection = $this->deleteRatings($name, $value);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: ' . count($documentCollection->getDocuments()) . ' documents affected');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
		}
	}

	/**
	 * @param string $name 
	 * @param string $value 
	 * @param int $rating 
	 * @return void
	 */
	public function updateCommand($name='artist', $value='ACDC', $rating=3) {
		$documentCollection = $this->deleteRatings($name, $value);
		$documentCollection = $this->createRatings($name, $rating, $documentCollection);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: ' . count($documentCollection->getDocuments()) . ' documents affected');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
		}
	}
		
}
