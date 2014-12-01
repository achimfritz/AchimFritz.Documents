<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Category;

/**
 * Testcase for CategoryRepository
 */
class CategoryRepositoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Repository\Exception
	 */
	public function removeThrowsExceptionIfCategoryHasDocuments() {
		$category = new Category();

		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Document', array('foo'));

		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentRepository', array('findByCategory'));
		$documentRepository->expects($this->once())->method('findByCategory')->with($category)->will($this->returnValue(array($document)));

		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('foo'));

		$this->inject($categoryRepository, 'documentRepository', $documentRepository);
		$categoryRepository->remove($category);
	}

}
?>
