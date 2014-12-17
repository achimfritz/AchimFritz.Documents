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

		$categoryRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\CategoryRepository', array('getPersistedOrAdd'));
		$categoryRepository->expects($this->once())->method('getPersistedOrAdd')->will($this->returnValue($category));

		$documentCollectionService = $this->getMock('AchimFritz\Documents\Domain\Service\DocumentCollectionService', array('foo'));
		$this->inject($documentCollectionService, 'documentRepository', $documentRepository);
		$this->inject($documentCollectionService, 'categoryRepository', $categoryRepository);

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

}
