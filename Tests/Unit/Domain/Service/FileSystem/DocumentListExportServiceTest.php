<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\FileSystem\DocumentListExportService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument;
use AchimFritz\Documents\Domain\Model\Mp3Document;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentListItem;

/**
 * Testcase for DocumentListExportService
 */
class DocumentListExportServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function exportCopiesFile() {
		$exportService = $this->getMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentListExportService', array('getName', 'createExportDirectory', 'createFromPath', 'createToPath', 'copyFile'));
		$documentList = new DocumentList();
		$document = new FileSystemDocument();
		$documentListItem = new DocumentListItem();
		$documentListItem->setDocument($document);
		$documentListItem->setSorting(1);
		$documentList->addDocumentListItem($documentListItem);
		$exportService->expects($this->once())->method('getName')->will($this->returnValue('foo'));
		$exportService->expects($this->once())->method('createExportDirectory')->will($this->returnValue('/foo'));
		$exportService->expects($this->once())->method('createFromPath')->with($document)->will($this->returnValue('bar'));
		$exportService->expects($this->once())->method('createToPath')->with($documentListItem, 0, '/foo')->will($this->returnValue('/foo/01_bar'));
		$exportService->expects($this->once())->method('copyFile')->with('bar', '/foo/01_bar');
		$cnt = $exportService->export($documentList);
		$this->assertSame(1, $cnt);
	}

	/**
	 * @test
	 */
	public function getNameReturnsPathFromCategory() {
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentListExportService', array('foo'));
		$category = new Category();
		$category->setPath('foo/bar');
		$documentList = new DocumentList();
		$documentList->setCategory($category);
		$name = $exportService->_call('getName', $documentList);
		$this->assertSame('foo_bar', $name);
	}


	/**
	 * @test
	 */
	public function createToPathReturnsDirectoryAndToName() {
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentListExportService', array('foo'));
		$document = new FileSystemDocument();
		$document->setName('foo/baz.jpg');
		$documentListItem = new DocumentListItem();
		$documentListItem->setDocument($document);
		$toPath = $exportService->_call('createToPath', $documentListItem, 0, '/bar');
		$this->assertSame('/bar/01_baz.jpg', $toPath);
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Service\FileSystem\Exception
	 */
	public function createFromPathThrowsExceptionForFileSystemDocument() {
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentListExportService', array('foo'));
		$document = new FileSystemDocument();
		$exportService->_call('createFromPath', $document);
	}

	/**
	 * @test
	 */
	public function createFromPathReturnsAbsolutePath() {
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentListExportService', array('foo'));
		$fileSystemFactory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\FileSystemFactory', array('create'));
		$fileSystem = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\FileSystem', array('getAbsolutePath'));
		$fileSystem->expects($this->once())->method('getAbsolutePath')->will($this->returnValue('foo'));
		$fileSystemFactory->expects($this->once())->method('create')->will($this->returnValue($fileSystem));
		$this->inject($exportService, 'mp3FileSystemFactory', $fileSystemFactory);
		$document = new Mp3Document();
		$from = $exportService->_call('createFromPath', $document);
		$this->assertSame('foo', $from);
	}

}
