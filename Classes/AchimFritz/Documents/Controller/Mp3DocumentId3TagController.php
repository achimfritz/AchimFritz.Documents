<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Mp3DocumentId3Tag;

class Mp3DocumentId3TagController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService
	 */
	protected $id3TagWriterService;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'mp3DocumentId3Tag';

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Mp3DocumentId3Tag $mp3DocumentId3Tag
	 * @return void
	 */
	public function updateAction(Mp3DocumentId3Tag $mp3DocumentId3Tag) {
		try {
			$this->id3TagWriterService->tagMp3DocumentId3Tag($mp3DocumentId3Tag);
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Document tagged');
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
			$this->redirect('index', 'Mp3Document', NULL, array('document' => $mp3DocumentId3Tag->getDocument()));
		}
	}

}
