<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

/**
 * Testcase for DocumentExportService
 */
class DocumentExportServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function exportCopiesFileAndZipDirectory() {
		$exportService = $this->getMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService', array('createExportDirectory', 'createFromPath', 'createToPath', 'copyFile', 'zipDirectory'));
		$documentExport = new DocumentExport();
		$document = new FileSystemDocument();
		$documentExport->addDocument($document);
		$exportService->expects($this->once())->method('createExportDirectory')->will($this->returnValue('/foo'));
		$exportService->expects($this->once())->method('createFromPath')->with($document, $documentExport)->will($this->returnValue('bar'));
		$exportService->expects($this->once())->method('createToPath')->with($document, $documentExport, 0, '/foo')->will($this->returnValue('/foo/bar'));
		$exportService->expects($this->once())->method('copyFile')->with('bar', '/foo/bar');
		$exportService->expects($this->once())->method('zipDirectory')->with('/foo');
		$name = $exportService->export($documentExport);
		$this->assertSame('/foo.zip', $name);
	}

	/**
	 * @test
	 */
	public function createFromPathReturnsAbsolutePathAsDefault() {
		$documentExport = new DocumentExport();
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\ImageDocument', array('getAbsolutePath'));
		$document->expects($this->once())->method('getAbsolutePath')->will($this->returnValue('foo'));
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService', array('foo'));
		$from = $exportService->_call('createFromPath', $document, $documentExport);
		$this->assertSame('foo', $from);
	}

	/**
	 * @test
	 */
	public function createFromPathReturnsThumbPathIfUseThumbIsTrue() {
		$documentExport = new DocumentExport();
		$documentExport->setUseThumb(TRUE);
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\ImageDocument', array('getAbsolutePath', 'getAbsoluteWebThumbPath'));
		$document->expects($this->once())->method('getAbsoluteWebThumbPath')->will($this->returnValue('foo'));
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService', array('foo'));
		$from = $exportService->_call('createFromPath', $document, $documentExport);
		$this->assertSame('foo', $from);
	}

	/**
	 * @test
	 */
	public function createToPathReturnsDirectoryAndFileNameByDefault() {
		$documentExport = new DocumentExport();
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\FileSystemDocument', array('getFileName'));
		$document->expects($this->once())->method('getFileName')->will($this->returnValue('file.txt'));
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService', array('foo'));
		$to = $exportService->_call('createToPath', $document, $documentExport, 0 , 'root');
		$this->assertSame('root/file.txt', $to);
	}

	/**
	 * @test
	 */
	public function createToPathReturnsDirectoryAndCounterPrefixedNameIfSortPrefixIsCounter() {
		$documentExport = new DocumentExport();
		$documentExport->setSortByPrefix(DocumentExport::SORT_BY_PREFIX_COUNTER);
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\FileSystemDocument', array('getFileName'));
		$document->expects($this->once())->method('getFileName')->will($this->returnValue('file.txt'));
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService', array('foo'));
		$to = $exportService->_call('createToPath', $document, $documentExport, 0 , 'root');
		$this->assertSame('root/0001_file.txt', $to);
	}


	/**
	 * @test
	 */
	public function createToPathReturnsDirectoryAndFullNameIfUseFullPathIsTrue() {
		$documentExport = new DocumentExport();
		$documentExport->setUseFullPath(TRUE);
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\FileSystemDocument', array('getFileName'));
		$document->expects($this->once())->method('getFileName')->will($this->returnValue('file.txt'));
		$exportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService', array('createFullPathDirectory'));
		$exportService->expects($this->once())->method('createFullPathDirectory')->with('root', $document)->will($this->returnValue('root/dir'));
		$to = $exportService->_call('createToPath', $document, $documentExport, 0 , 'root');
		$this->assertSame('root/dir/file.txt', $to);
	}

}
