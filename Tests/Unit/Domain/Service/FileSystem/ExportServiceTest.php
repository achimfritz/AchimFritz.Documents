<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\FileSystem\ExportService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

/**
 * Testcase for ExportService
 */
class ExportServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {



	/**
	 * @test
	 */
	public function exportCopiesFile() {
		#$renameService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\RenameService', array('foo'));
		$exportService = $this->getMock('AchimFritz\Documents\Domain\Service\FileSystem\ExportService', array('createExportDirectory', 'createFromPath', 'createToPath', 'copyFile'));
		$documentExport = new DocumentExport();
		$document = new FileSystemDocument();
		$documentExport->addDocument($document);
		$exportService->expects($this->once())->method('createExportDirectory')->with($documentExport)->will($this->returnValue('/foo'));
		$exportService->expects($this->once())->method('createFromPath')->with($document, $documentExport)->will($this->returnValue('bar'));
		$exportService->expects($this->once())->method('createToPath')->with($document, $documentExport, 0, '/foo')->will($this->returnValue('/foo/bar'));
		$exportService->expects($this->once())->method('copyFile')->with('bar', '/foo/bar');
		$cnt = $exportService->export($documentExport);
		$this->assertSame(1, $cnt);
	}

}
