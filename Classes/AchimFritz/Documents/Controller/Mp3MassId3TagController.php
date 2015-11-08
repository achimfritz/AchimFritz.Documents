<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Facet\RenameCategory;

class Mp3MassId3TagController extends RestController {

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\Mp3Document\RenameId3TagsService
	 * @Flow\Inject
	 */
	protected $renameId3TagsService;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'renameCategory';

	/**
	 * @param \AchimFritz\Documents\Domain\Facet\RenameCategory $renameCategory
	 * @return void
	 */
	public function updateAction(RenameCategory $renameCategory) {
		try {
			$this->renameId3TagsService->rename($renameCategory);
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('documents updated');
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->handleException($e);
		}
		$this->redirect('index', 'Mp3Document');
	}
}
