<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Facet\RenameCategory;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class RenameCategoryService {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 */
	protected $pathService;

	/**
	 * @param \AchimFritz\Documents\Domain\Facet\RenameCategory $renameCategory
	 * @throws \AchimFritz\Documents\Domain\Service\Exception
	 * @throws \AchimFritz\Documents\Domain\Repository\Exception
	 * @return integer
	 */
	public function renameCategories(RenameCategory $renameCategory) {
		$cnt = 0;
		$categories = $this->categoryRepository->findByPathHead($renameCategory->getOldPath());
		if (count($categories) === 0) {
			$category = $this->categoryRepository->findOneByPath($renameCategory->getOldPath());
			if ($category instanceof Category === FALSE) {
				throw new Exception('no categories found with path ' . $renameCategory->getOldPath(), 1418141481);
			}
			$categories = array($category);
		}
		$existingCategory = $this->categoryRepository->findOneByPath($renameCategory->getNewPath());
		foreach ($categories AS $category) {
			if ($existingCategory instanceof Category === TRUE) {
				$this->changeCategory($existingCategory, $category);
			} else {
				$this->updatePath($category, $renameCategory);
			}
			$cnt++;
		}
		return $cnt;
	}

	/**
	 * @param Category $category 
	 * @param RenameCategory $renameCategory 
	 * @return void
	 */
	protected function updatePath(Category $category, RenameCategory $renameCategory) {
		$newPath = $this->pathService->replacePath($category->getPath(), $renameCategory->getOldPath(), $renameCategory->getNewPath());
		$existingCategory = $this->categoryRepository->findOneByPath($newPath);
		if ($existingCategory instanceof Category === TRUE) {
			$this->changeCategory($existingCategory, $category);
		} else {
			$category->setPath($newPath);
			$this->categoryRepository->update($category);
		}

	}

	/**
	 * @param Category $existingCategory 
	 * @param Category $category 
	 * @throws \AchimFritz\Documents\Domain\Repository\Exception
	 * @return void
	 */
	protected function changeCategory(Category $existingCategory, Category $category) {
		$documents = $this->documentRepository->findByCategory($category);
		foreach ($documents AS $document) {
			$document->removeCategory($category);
			$document->addCategory($existingCategory);
			$this->documentRepository->update($document);
		}
		$this->categoryRepository->remove($category);
	}

}
