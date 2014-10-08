<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \AchimFritz\Documents\Domain\Model\Category;

/**
 * Category controller for the AchimFritz.Documents package 
 *
 * @Flow\Scope("singleton")
 */
class CategoryPathController extends AbstractActionController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentsPersistenceManager;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'path';

	/**
	 * Shows a list of categories
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('categories', $this->categoryRepository->findAll());
	}

	/**
	 * Shows a single category object
	 *
	 * @param string $path
	 * @return void
	 */
	public function showAction($path) {
		$categories = $this->categoryRepository->findByPath($path);
		$self = $this->categoryRepository->findOneByPath($path);
		$found = TRUE;
		if (!$self instanceof Category AND count($categories) === 0) {
			$this->addWarningMessage('no categories found');
			$found = FALSE;
		}
		$this->view->assign('found', $found);
		$this->view->assign('self', $self);
		$this->view->assign('categories', $categories);
		$this->view->assign('path', $path);
	}

	/**
	 * Removes the given category object from the category repository
	 *
	 * @param string $path
	 * @return void
	 */
	public function deleteAction($path) {
		$format = $this->request->getFormat();
		$categories = $this->categoryRepository->findByPath($path);
		$self = $this->categoryRepository->findOneByPath($path);
		$found = TRUE;
		if (!$self instanceof Category AND count($categories) === 0) {
			$this->addWarningMessage('no categories found');
			$found = FALSE;
		}
		foreach ($categories AS $category) {
			try {
				$this->categoryRepository->remove($category);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category deleted ' . $category->getPath());
			} catch (\Exception $e) {
				$this->addErrorMessage('cannot delete category ' . $category->getPath());
				$this->handleException($e);
			}
		}
		if ($self instanceof Category) {
			try {
				$this->categoryRepository->remove($self);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category deleted ' . $self->getPath());
			} catch (\Exception $e) {
				$this->addErrorMessage('cannot delete category ' . $self->getPath());
				$this->handleException($e);
			}
		}
		if ($format === 'html') {
			$this->redirect('list');
		}
	}

}

?>
