<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\DocumentCollectionService;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentCollection;
use AchimFritz\Documents\Domain\Repository\DocumentRepository;
use AchimFritz\Documents\Domain\Repository\CategoryRepository;

/**
 * Testcase for DocumentCollectionService
 */
class DocumentCollectionServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * @test
	 */
	public function mergeAddCategoryToDocument() {
		$documentCollection = new DocumentCollection();
		$category = new Category();

		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Document', array('addCategory'));
		$document->expects($this->once())->method('addCategory')->with($category);

		$documentCollection->setCategory($category);
		$documentCollection->addDocument($document);

		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentRepository', array('update'));
		$documentRepository->expects($this->once())->method('update')->with($document);

		$documentCollectionService = $this->getMock('AchimFritz\Documents\Domain\Service\DocumentCollectionService', array('checkPersisted'));
		$documentCollectionService->expects($this->once())->method('checkPersisted')->with($category)->will($this->returnValue($category));
		$this->inject($documentCollectionService, 'documentRepository', $documentRepository);

		$documentCollectionService->merge($documentCollection);
	}

	/**
	 * @test
	 */
	public function removeRemovesCategoryFromDocument() {
		$documentCollection = new DocumentCollection();
		$category = new Category();
		$persisted = new Category();
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Document', array('removeCategory'));
		$document->addCategory($persisted);
		$document->expects($this->once())->method('removeCategory')->with($persisted);

		$documentCollection->setCategory($category);
		$documentCollection->addDocument($document);

		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentRepository', array('update'));
		$documentRepository->expects($this->once())->method('update')->with($document);

		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath'));
		$categoryRepository->expects($this->once())->method('findOneByPath')->will($this->returnValue($persisted));

		$documentCollectionService = $this->getMock('AchimFritz\Documents\Domain\Service\DocumentCollectionService', array('foo'));
		$this->inject($documentCollectionService, 'categoryRepository', $categoryRepository);
		$this->inject($documentCollectionService, 'documentRepository', $documentRepository);
		$documentCollectionService->remove($documentCollection);
	}

	/**
	 * @test
	 */
	public function checkPersistedReturnsThePersistedCategoryIfFound() {
		$persisted = new Category();
		$category = new Category();
		$category->setPath('foo');
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath'));
		$categoryRepository->expects($this->once())->method('findOneByPath')->with('foo')->will($this->returnValue($persisted));
		$documentCollectionService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\DocumentCollectionService', array('foo'));
		$this->inject($documentCollectionService, 'categoryRepository', $categoryRepository);
		$res = $documentCollectionService->_call('checkPersisted', $category);
		$this->assertSame($persisted, $res);

	}

	/**
	 * @test
	 */
	public function checkPersistedAddsCategoryToRepositoryIfNoPersistedIsFound() {
		$category = new Category();
		$category->setPath('foo');
		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('findOneByPath', 'add'));
		$categoryRepository->expects($this->once())->method('findOneByPath')->with('foo')->will($this->returnValue(NULL));
		$categoryRepository->expects($this->once())->method('add')->with($category);
		$documentCollectionService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\DocumentCollectionService', array('foo'));
		$this->inject($documentCollectionService, 'categoryRepository', $categoryRepository);
		$res = $documentCollectionService->_call('checkPersisted', $category);
		$this->assertSame($category, $res);

	}

}
?>
