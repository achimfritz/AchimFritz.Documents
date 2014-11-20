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
	 */
	public function removeRemoveCategoryFromDocuments() {
		$category = new Category();

		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Document', array('removeCategory'));
		$document->expects($this->once())->method('removeCategory')->with($category);
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('parentRemove'));
		$categoryRepository->expects($this->once())->method('parentRemove')->with($category);

		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentRepository', array('update', 'findByCategory'));
		$documentRepository->expects($this->once())->method('findByCategory')->with($category)->will($this->returnValue(array($document)));
		$documentRepository->expects($this->once())->method('update')->with($document);

		$this->inject($categoryRepository, 'documentRepository', $documentRepository);
		$categoryRepository->remove($category);

	}

}
?>
