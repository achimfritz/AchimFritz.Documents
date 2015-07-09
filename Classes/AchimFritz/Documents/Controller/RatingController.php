<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Facet\Rating;

/**
 * @Flow\Scope("singleton")
 */
class RatingController extends \AchimFritz\Rest\Controller\RestController {

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
	 * @param AchimFritz\Documents\Domain\Model\Facet\Rating;
	 * @return void
	 */
	public function deleteAction(Rating $rating) {
		$documentCollection = $this->ratingService->deleteRatings($rating);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage(count($documentCollection->getDocuments()) . ' documents affected');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * @param AchimFritz\Documents\Domain\Model\Facet\Rating;
	 * @return void
	 */
	public function updateAction(Rating $rating) {
		$documentCollection = $this->ratingService->updateRatings($rating);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage(count($documentCollection->getDocuments()) . ' documents affected');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
	}
		
}
