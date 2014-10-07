<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Exception;

/**
 * Category controller for the AchimFritz.Documents package 
 *
 * @Flow\Scope("singleton")
 */
abstract class AbstractCategoryController extends AbstractActionController {

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
	 * updateCategory 
	 * 
	 * @param Category $category 
	 * @return Category
	 */
	protected function updateCategory(Category $category) {
		try {
			$this->categoryRepository->update($category);
			$this->documentsPersistenceManager->persistAll();
			$this->addOkMessage('category updated ' . $category->getPath());
		} catch (Exception $e) {
			$this->addErrorMessage('cannot update category ' . $category->getPath());
			$this->handleException($e);
		}
		return $category;
	}

	/**
	 * createCategory 
	 * 
	 * @param Category $category 
	 * @return Category
	 */
	protected function createCategory(Category $category) {
		try {
			$this->categoryRepository->add($category);
			$this->documentsPersistenceManager->persistAll();
			$this->addOkMessage('category created ' . $category->getPath());
		} catch (Exception $e) {
			$this->addErrorMessage('cannot create category ' . $category->getPath());
			$this->handleException($e);
		}
		return $category;
	}

	/**
	 * deleteCategory 
	 * 
	 * @param Category $category 
	 * @return void
	 */
	protected function deleteCategory(Category $category) {
		try {
			$this->categoryRepository->remove($category);
			$this->documentsPersistenceManager->persistAll();
			$this->addOkMessage('category deleted ' . $category->getPath());
		} catch (Exception $e) {
			$this->addErrorMessage('cannot delete category ' . $category->getPath());
			$this->handleException($e);
		}
	}
}

?>
