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
class CategoryDiffController extends AbstractCategoryController {

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
	public function updateAction(Category $category) {
		$format = $this->request->getFormat();
		$existingCategory = $this->categoryRepository->findOneByPath($category->getPath());
		if (!$existingCategory instanceof Category) {
			$this->addWarningMessage('category not found ' . $category->getPath());
		} else {
			$existingCategory->removeAllDocumentsFromCategory($category);
			try {
				$documents = $category->getDocuments();
				foreach ($documents as $document) {
					$this->documentRepository->update($document);
				}
				$this->categoryRepository->update($existingCategory);
				$this->documentsPersistenceManager->persistAll();
				$this->addOkMessage('category updated ' . $existingCategory->getPath());
			} catch (\Exception $e) {
				$this->addErrorMessage('cannot update category ' . $existingCategory->getPath());
				$this->handleException($e);
			}
		}
		if ($format === 'html') {
			$this->redirect('show', 'Category', NULL, array('category' => $existingCategory));
		}
	}

}

?>
