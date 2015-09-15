<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Folder;

class Mp3FolderController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\Mp3Document\FolderService
	 */
	protected $folderService;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'folder';

	/**
	 * @param \AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Folder
	 * @return void
	 */
	public function updateAction(Folder $folder) {
		try {
			$this->folderService->update($folder);
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Documents tagged');
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->handleException($e);
		}
		if ($this->request->getReferringRequest() instanceof ActionRequest) {
			$this->redirectToRequest($this->request->getReferringRequest());
		} else {
			$this->redirect('index', 'Mp3Document');
		}
	}

}
