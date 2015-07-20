<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\Rating;

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
	 * @var \AchimFritz\Documents\Domain\Service\RatingService
	 */
	protected $ratingService;

	/**
	 * @param string $name 
	 * @param string $value 
	 * @return void
	 */
	public function deleteCommand($name='artist', $value='ACDC') {
		$rating = new Rating();
		$rating->setName($name);
		$rating->setValue($value);
		try {
			$documentCollection = $this->ratingService->deleteRatings($rating);
		} catch(\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
			$this->quit();
		}
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
	 * @param int $rate
	 * @return void
	 */
	public function updateCommand($name='artist', $value='ACDC', $rate=3) {
		$rating = new Rating();
		$rating->setName($name);
		$rating->setValue($value);
		$rating->setRate($rate);
		try {
			$documentCollection = $this->ratingService->updateRatings($rating);
		} catch(\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
			$this->quit();
		}
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: ' . count($documentCollection->getDocuments()) . ' documents affected');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
		}
	}
		
}
