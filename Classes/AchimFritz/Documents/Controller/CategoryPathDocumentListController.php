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

class CategoryPathDocumentListController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'categoryPath';

	/**
	 * @param string $categoryPath
	 * @return void
	 */
	public function showAction($categoryPath) {
		$documentList = $this->documentListRepository->findOneByCategoryPath($categoryPath);
		if ($documentList instanceof DocumentList === FALSE) {
			$this->addFlashMessage('no documentList found for ' . $categoryPath, '', Message::SEVERITY_ERROR);
		}
		$this->view->assign('categoryPath', $categoryPath);
		$this->view->assign('documentList', $documentList);
	}
}
