<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Policy;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Policy\DocumentPolicy;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\DocumentListItem;

/**
 * Testcase for DocumentPolicy
 */
class DocumentPolicyTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function categoryMayBeRemovedReturnsFalseIfDocumentListItemExists() {
		$category = new Category();
		$document = new Document();
		$documentList = $this->getMock('AchimFritz\Documents\Domain\Model\DocumentList', array('hasDocument'));
		$documentList->expects($this->once())->method('hasDocument')->will($this->returnValue(TRUE));

		$documentPolicy = new DocumentPolicy();

		$documentListRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentListRepository', array('findOneByCategory'));
		$documentListRepository->expects($this->once())->method('findOneByCategory')->will($this->returnValue($documentList));

		$this->inject($documentPolicy, 'documentListRepository', $documentListRepository);
		$res = $documentPolicy->categoryMayBeRemoved($category, $document);
		$this->assertSame(FALSE, $res);
	}

	/**
	 * @test
	 */
	public function categoryMayBeRemovedReturnsFalseIfDocumentListItemNotExistsAndDocumentHasAlreadyCategory() {
		$category = new Category();
		$document = new Document();
		$documentList = $this->getMock('AchimFritz\Documents\Domain\Model\DocumentList', array('hasDocument'));
		$documentList->expects($this->once())->method('hasDocument')->will($this->returnValue(TRUE));

		$documentPolicy = new DocumentPolicy();

		$documentListRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentListRepository', array('findOneByCategory'));
		$documentListRepository->expects($this->once())->method('findOneByCategory')->will($this->returnValue($documentList));

		$this->inject($documentPolicy, 'documentListRepository', $documentListRepository);
		$res = $documentPolicy->categoryMayBeRemoved($category, $document);
		$this->assertSame(FALSE, $res);
	}

	/**
	 * @test
	 */
	public function categoryMayBeRemovedReturnsTrue() {
		$category = new Category();
		$document = new Document();
		$document->addCategory($category);
		$documentList = $this->getMock('AchimFritz\Documents\Domain\Model\DocumentList', array('hasDocument'));
		$documentList->expects($this->once())->method('hasDocument')->will($this->returnValue(FALSE));

		$documentPolicy = new DocumentPolicy();

		$documentListRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\DocumentListRepository', array('findOneByCategory'));
		$documentListRepository->expects($this->once())->method('findOneByCategory')->will($this->returnValue($documentList));

		$this->inject($documentPolicy, 'documentListRepository', $documentListRepository);
		$res = $documentPolicy->categoryMayBeRemoved($category, $document);
		$this->assertSame(TRUE, $res);
	}

}
