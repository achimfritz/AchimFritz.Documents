<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Exception;

/**
 * Category controller for the AchimFritz.Documents package 
 *
 * @Flow\Scope("singleton")
 */
class CategoryMergeController extends AbstractCategoryController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'category';


	/**
	 * Removes the given category object from the category repository
	 *
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function createAction(Category $category) {
		$existingCategory = $this->categoryRepository->findOneByPath($category->getPath());
		if ($existingCategory instanceof Category) {
			$existingCategory->addAllDocumentsFromCategory($category);
			try {
				$this->categoryRepository->update($existingCategory);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category merged into ' . $existingCategory->getPath());
				$this->redirect('index', 'Category', NULL, array('category' => $existingCategory));
			} catch (Exception $e) {
				$this->addErrorMessage('cannot update category ' . $existingCategory->getPath());
				$this->handleException($e);
			}
		} else {
			try {
				$this->categoryRepository->add($category);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category created ' . $category->getPath());
				$this->redirect('index', 'Category', NULL, array('category' => $category));
			} catch (Exception $e) {
				$this->addErrorMessage('cannot create category ' . $category->getPath());
				$this->handleException($e);
			}
		}
		$this->redirect('index', 'Category');
	}

}

?>
