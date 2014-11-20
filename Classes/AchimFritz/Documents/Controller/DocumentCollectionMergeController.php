<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentCollection;
use AchimFritz\Documents\Domain\Model\Category;

class DocumentCollectionMergeController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\DocumentCollectionService
	 */
	protected $documentCollectionService;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'documentCollection';

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentCollection $documentCollection
	 * @return void
	 */
	public function createAction(DocumentCollection $documentCollection) {
		$cnt = $this->documentCollectionService->merge($documentCollection);
		$this->addFlashMessage($cnt . ' Documents updated.');
		$this->redirectToRequest($this->request->getReferringRequest());
	}

}
