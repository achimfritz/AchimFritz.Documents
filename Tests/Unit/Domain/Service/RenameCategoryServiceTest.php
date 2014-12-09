<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\RenameCategoryService;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\RenameCategory;
use AchimFritz\Documents\Domain\Repository\CategoryRepository;

/**
 * Testcase for RenameCategoryService
 */
class RenameCategoryServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * @test
	 */
	public function renameSetsNewPathToCategory() {
		$renameCategory = new RenameCategory();
		$renameCategory->setNewPath('foo/bar');
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath'));
		$category = new Category();
		$categoryRepository->expects($this->once())->method('findOneByPath')->will($this->returnValue($category));
		$renameCategoryService = new RenameCategoryService();
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$category = $renameCategoryService->rename($renameCategory);
		$this->assertSame($category->getPath(), 'foo/bar');
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Service\Exception
	 */
	public function renameThrowsExeptionIfOldPathIsNotFound() {
		$renameCategory = new RenameCategory();
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath'));
		$categoryRepository->expects($this->once())->method('findOneByPath')->will($this->returnValue(NULL));
		$renameCategoryService = new RenameCategoryService();
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$renameCategoryService->rename($renameCategory);
	}

}
