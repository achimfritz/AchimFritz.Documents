<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Facet\Rating;

/**
 * @Flow\Scope("singleton")
 */
class RatingController extends RestController {

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\RatingService
	 */
	protected $ratingService;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'rating';

	/**
	 * @param AchimFritz\Documents\Domain\Facet\Rating;
	 * @return void
	 */
	public function deleteAction(Rating $rating) {
		try {
			$documentCollection = $this->ratingService->deleteRatings($rating);
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage(count($documentCollection->getDocuments()) . ' documents affected');
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->handleException($e);
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * @param AchimFritz\Documents\Domain\Facet\Rating;
	 * @return void
	 */
	public function updateAction(Rating $rating) {
		try {
			$documentCollection = $this->ratingService->updateRatings($rating);
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage(count($documentCollection->getDocuments()) . ' documents affected');
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->handleException($e);
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
	}
		
}
