<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentList;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionRequest;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

class DocumentListController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'documentList';

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('documentLists', $this->documentListRepository->findAll());
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return void
	 */
	public function showAction(DocumentList $documentList) {
		$this->view->assign('documentList', $documentList);
	}

}
