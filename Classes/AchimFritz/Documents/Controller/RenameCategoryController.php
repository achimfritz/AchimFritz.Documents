<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Facet\RenameCategory;

class RenameCategoryController extends \AchimFritz\Rest\Controller\RestController {

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
				$this->addFlashMessage('Cannot Update the categories ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
			}
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->addFlashMessage('Cannot rename with: ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
			$this->response->setStatus(500);
		}
	}
}
