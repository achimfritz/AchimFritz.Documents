<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Cddb;

class CddbController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService
	 */
	protected $cddbService;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'cddb';

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Cddb
	 * @return void
	 */
	public function updateAction(Cddb $cddb) {
		try {
			$this->cddbService->writeId3Tags($cddb);
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Documents tagged');
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception $e) {
			$this->handleException($e);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->handleException($e);
		} catch (\SolrClientException $e) {
			$this->handleException($e);
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}

		if ($this->request->getReferringRequest() instanceof ActionRequest) {
			$this->redirectToRequest($this->request->getReferringRequest());
		} else {
			$this->redirect('index', 'Mp3Document');
		}
	}

}
