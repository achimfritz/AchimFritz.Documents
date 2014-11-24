<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Factory\DocumentCollectionFactory;
use AchimFritz\Documents\Domain\Model\Document;

/**
 * Testcase for FileSystemDocument
 */
class DocumentCollectionFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function createInCategoriesSetsCategory() {	
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentRepository', array('findInAllCategories'));
		$documentRepository->expects($this->once())->method('findInAllCategories')->will($this->returnValue(array()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Factory\DocumentCollectionFactory', array('foo'));
		$this->inject($factory, 'documentRepository', $documentRepository);
		$categories = new \Doctrine\Common\Collections\ArrayCollection();
		$documentCollection = $factory->createInCategories('foo', $categories);
		$this->assertSame('foo', $documentCollection->getCategory()->getPath());
	}

	/**
	 * @test
	 */
	public function createInCategoriesSetsDocuments() {
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentRepository', array('findInAllCategories'));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Factory\DocumentCollectionFactory', array('foo'));
		$document = new Document();
		$documentRepository->expects($this->once())->method('findInAllCategories')->will($this->returnValue(array($document)));
		$this->inject($factory, 'documentRepository', $documentRepository);
		$categories = new \Doctrine\Common\Collections\ArrayCollection();
		$documentCollection = $factory->createInCategories('foo', $categories);
		$res = $documentCollection->getDocuments()->current();
		$this->assertSame($document, $res);
	}

}
?>
