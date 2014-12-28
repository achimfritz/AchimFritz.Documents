<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\RenameCategory;
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
	 * @param \AchimFritz\Documents\Domain\Model\Facet\RenameCategory $renameCategory
	 * @throws \AchimFritz\Documents\Domain\Service\Exception
	 * @return integer
	 */
	public function rename(RenameCategory $renameCategory) {
		$category = $this->categoryRepository->findOneByPath($renameCategory->getOldPath());
		if ($category instanceof Category === FALSE) {
			throw new Exception('no category found with path ' . $renameCategory->getOldPath(), 1418141481);
		}
		$category->setPath($renameCategory->getNewPath());
		return $category;
	}

}
