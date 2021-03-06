<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Facet\Rating;
use AchimFritz\Documents\Domain\Facet\DocumentCollection;

/**
 * @Flow\Scope("singleton")
 */
class RatingService {

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
	 * @param AchimFritz\Documents\Domain\Facet\Rating;
	 * @return \AchimFritz\Documents\Domain\Facet\DocumentCollection;
	 * @throws \AchimFritz\Documents\Domain\Service\Exception
	 */
	public function deleteRatings(Rating $rating) {
		$documentCollection = $this->getDocumentCollection($rating);
		$categories = $this->categoryRepository->findByPathHead('rating/' . $rating->getName());
		foreach ($categories as $category) {
			$documentCollection->setCategory($category);
			$this->documentCollectionService->remove($documentCollection);
		}
		return $documentCollection;
	}

	/**
	 * @param AchimFritz\Documents\Domain\Facet\Rating;
	 * @return void
	 * @throws \AchimFritz\Documents\Domain\Service\Exception
	 */
	public function updateRatings(Rating $rating) {
		$documentCollection = $this->deleteRatings($rating);
		$documentCollection = $this->createRatings($rating, $documentCollection);
		return $documentCollection;
	}
		
	/**
	 * @param AchimFritz\Documents\Domain\Facet\Rating;
	 * @param \AchimFritz\Documents\Domain\Facet\DocumentCollection;
	 * @return \AchimFritz\Documents\Domain\Facet\DocumentCollection;
	 */
	protected function createRatings(Rating $rating, DocumentCollection $documentCollection) {
		$category = new Category();
		$category->setPath('rating/' . $rating->getName() . '/' . (string)$rating->getRate());
		$documentCollection->setCategory($category);
		$this->documentCollectionService->merge($documentCollection);
		return $documentCollection;
	}

	/**
	 * @param AchimFritz\Documents\Domain\Facet\Rating
	 * @return \AchimFritz\Documents\Domain\Facet\DocumentCollection
	 * @throws \AchimFritz\Documents\Domain\Service\Exception
	 */
	protected function getDocumentCollection(Rating $rating) {
		switch ($rating->getName()) {
			case 'title':
				$documents = $this->documentRepository->findByName($rating->getValue());
				break;
			case 'album':
			case 'artist':
				$fq = $rating->getName() . ':"' . $rating->getValue() . '"';
				$docs = $this->solrHelper->findDocumentsByFq($fq);
				$documents = $this->documentRepository->findByNames($docs);
				break;
			default:
				throw new Exception('unknown name ' . $rating->getName(), 1437392282);
		}
		if (count($documents) === 0) {
			throw new Exception('no documents found for ' . $rating->getName() . ':' . $rating->getValue(), 1437392281);
		}
		$documentCollection = new DocumentCollection();
		foreach ($documents as $document) {
			$documentCollection->addDocument($document);
		}
		return $documentCollection;
	}
}
