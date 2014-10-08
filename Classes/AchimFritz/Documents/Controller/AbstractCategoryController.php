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
		// we cannot update if category with same path already exists -> merge and delete
		$existingCategory = $this->categoryRepository->findOneByPath($category->getPath());
		if ($existingCategory instanceof Category AND $existingCategory !== $category) {
			$existingCategory->addAllDocumentsFromCategory($category);
			$this->deleteCategory($category);
			try {
				$this->categoryRepository->update($existingCategory);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category merged into ' . $existingCategory->getPath());
			} catch (\Exception $e) {
				$this->addErrorMessage('cannot update category ' . $existingCategory->getPath());
				$this->handleException($e);
			}
			return $existingCategory;
		} else {
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
	}

	/**
	 * createCategory 
	 * 
	 * @param Category $category 
	 * @return Category
	 */
	protected function createCategory(Category $category) {
		// we cannot create if category with same path already exists -> merge
		$existingCategory = $this->categoryRepository->findOneByPath($category->getPath());
		if ($existingCategory instanceof Category) {
			$existingCategory->addAllDocumentsFromCategory($category);
			try {
				$this->categoryRepository->update($existingCategory);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category merged into ' . $existingCategory->getPath());
			} catch (Exception $e) {
				$this->addErrorMessage('cannot update category ' . $existingCategory->getPath());
				$this->handleException($e);
			}
			return $existingCategory;
		} else {
			try {
				$this->categoryRepository->add($category);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category created ' . $category->getPath());
			} catch (\Exception $e) {
				$this->addErrorMessage('cannot create category ' . $category->getPath());
				$this->handleException($e);
			}
			return $category;
		}
	}

	/**
	 * deleteCategory 
	 * 
	 * @param Category $category 
	 * @return void
	 */
	protected function deleteCategory(Category $category) {
		// we just remove documents and delete only if no documents remain
		$existingCategory = $this->categoryRepository->findOneByPath($category->getPath());
		if (!$existingCategory instanceof Category) {
			$this->addErrorMessage('category not found ' . $category->getPath());
		} else {
			$existingCategory->removeAllDocumentsFromCategory($category);
			try {
				if (count($existingCategory->getDocuments()) === 0) {
					$this->categoryRepository->remove($existingCategory);
					$this->documentsPersistenceManager->persistAll();
					$this->addOkMessage('category removed' . $existingCategory->getPath());
				} else {
					$this->categoryRepository->update($existingCategory);
					$this->documentsPersistenceManager->persistAll();
					$this->addOkMessage('category updated ' . $existingCategory->getPath());
				}
				//$documents = $category->getDocuments();
				#foreach ($documents as $document) {
				#	$this->documentRepository->update($document);
				#}
			} catch (\Exception $e) {
				$this->addErrorMessage('cannot delete category ' . $existingCategory->getPath());
				$this->handleException($e);
			}
		}
	}
}

?>
