<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Facet\RenameCategory;

class RenameCategoryController extends RestController {

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\RenameCategoryService
	 */
	protected $renameCategoryService;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'renameCategory';

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Facet\RenameCategory $renameCategory
	 * @return void
	 */
	public function updateAction(RenameCategory $renameCategory) {
		try {
			$cnt = $this->renameCategoryService->renameCategories($renameCategory);
			try {
				$this->documentPersistenceManager->persistAll();
				$this->addFlashMessage('Updated ' . $cnt . ' categories.');
			} catch (\AchimFritz\Documents\Persistence\Exception $e) {
				$this->handleException($e);
			}
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->handleException($e);
		} catch (\AchimFritz\Documents\Domain\Repository\Exception $e) {
			$this->handleException($e);
		}
		$this->redirect('index', 'Category');
	}
}
