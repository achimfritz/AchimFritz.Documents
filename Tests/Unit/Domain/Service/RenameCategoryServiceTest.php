<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\RenameCategoryService;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Facet\RenameCategory;
use AchimFritz\Documents\Domain\Repository\CategoryRepository;

/**
 * Testcase for RenameCategoryService
 */
class RenameCategoryServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * @test
	 */
	public function renameChangeCategoryIfExistingCategoryIsFound() {
		$renameCategory = new RenameCategory();
		$renameCategory->setNewPath('foo');
		$renameCategory->setOldPath('bar');
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath', 'findByPathHead'));
		$category = new Category();
		$existingCategory = new Category();
		$categoryRepository->expects($this->once())->method('findOneByPath')->with('foo')->will($this->returnValue($existingCategory));
		$categoryRepository->expects($this->once())->method('findByPathHead')->with('bar')->will($this->returnValue(array($category)));
		$renameCategoryService = $this->getMock('AchimFritz\Documents\Domain\Service\RenameCategoryService', array('changeCategory'));
		$renameCategoryService->expects($this->once())->method('changeCategory')->with($existingCategory, $category);
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$cnt = $renameCategoryService->renameCategories($renameCategory);
		$this->assertSame(1, $cnt);
	}

	/**
	 * @test
	 */
	public function renameUpdatePathIfExistingCategoryIsNotFound() {
		$renameCategory = new RenameCategory();
		$renameCategory->setNewPath('foo');
		$renameCategory->setOldPath('bar');
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath', 'findByPathHead'));
		$category = new Category();
		$existingCategory = NULL;
		$categoryRepository->expects($this->once())->method('findOneByPath')->with('foo')->will($this->returnValue($existingCategory));
		$categoryRepository->expects($this->once())->method('findByPathHead')->with('bar')->will($this->returnValue(array($category)));
		$renameCategoryService = $this->getMock('AchimFritz\Documents\Domain\Service\RenameCategoryService', array('updatePath'));
		$renameCategoryService->expects($this->once())->method('updatePath')->with($category, $renameCategory);
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$cnt = $renameCategoryService->renameCategories($renameCategory);
		$this->assertSame(1, $cnt);
	}

	/**
	 * @test
	 */
	public function updatePathCallsChangeCategoryIfCategoryWithPathIsFound() {

		$renameCategory = new RenameCategory();
		$category = new Category();
		$existingCategory = new Category();

		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath'));
		$categoryRepository->expects($this->once())->method('findOneByPath')->will($this->returnValue($existingCategory));

		$renameCategoryService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\RenameCategoryService', array('changeCategory'));
		$renameCategoryService->expects($this->once())->method('changeCategory')->with($existingCategory, $category);

		$pathService = new PathService();
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$this->inject($renameCategoryService, 'pathService', $pathService);
		$renameCategoryService->_call('updatePath', $category, $renameCategory);
	}

	/**
	 * @test
	 */
	public function updatePathSetsNewPath() {
		$renameCategory = new RenameCategory();
		$renameCategory->setNewPath('foo/bar');
		$renameCategory->setOldPath('foo/baz');
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('update', 'findOneByPath'));
		$category = $this->getMock('AchimFritz\Documents\Domain\Model\Category', array('setPath', 'getPath'));
		$category->expects($this->once())->method('getPath')->will($this->returnValue('foo/baz/x'));
		$category->expects($this->once())->method('setPath')->with('foo/bar/x');
		$categoryRepository->expects($this->once())->method('findOneByPath')->will($this->returnValue(NULL));
		$categoryRepository->expects($this->once())->method('update')->with($category);
		$renameCategoryService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\RenameCategoryService', array('foo'));
		$pathService = new PathService();
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$this->inject($renameCategoryService, 'pathService', $pathService);
		$renameCategoryService->_call('updatePath', $category, $renameCategory);
	}

	/**
	 * @test
	 */
	public function changeCategoryReplaceCategoryOnAllDocuments() {
		$category = new Category();
		$existingCategory = new Category();

		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Document', array('removeCategory', 'addCategory'));
		$document->expects($this->once())->method('removeCategory')->with($category);
		$document->expects($this->once())->method('addCategory')->with($existingCategory);
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('remove'));
		$categoryRepository->expects($this->once())->method('remove')->with($category);
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentRepository', array('findByCategory', 'update'));
		$documentRepository->expects($this->once())->method('findByCategory')->will($this->returnValue(array($document)));
		$documentRepository->expects($this->once())->method('update')->with($document);

		$renameCategoryService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\RenameCategoryService', array('foo'));
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$this->inject($renameCategoryService, 'documentRepository', $documentRepository);

		$renameCategoryService->_call('changeCategory', $existingCategory, $category);
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Service\Exception
	 */
	public function renameThrowsExeptionIfOldPathIsNotFound() {
		$renameCategory = new RenameCategory();
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findByPathHead', 'findOneByPath'));
		$categoryRepository->expects($this->once())->method('findByPathHead')->will($this->returnValue(array()));
		$categoryRepository->expects($this->once())->method('findOneByPath')->will($this->returnValue(NULL));
		$renameCategoryService = new RenameCategoryService();
		$this->inject($renameCategoryService, 'categoryRepository', $categoryRepository);
		$renameCategoryService->renameCategories($renameCategory);
	}

}
