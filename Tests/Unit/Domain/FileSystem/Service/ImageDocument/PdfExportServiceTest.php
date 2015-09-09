<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\FileSystem\Service\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */


use AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\PdfExport;

/**
 * Testcase for DocumentExportService
 */
class PdfExportServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getCommandReturnsDefaultCommand() {
		$defaultCommand = 'montage -tile 3x5 -geometry 751x563+37+37 -density 300x300 /tmp/out.pdf';
		$pdfExportService = $this->getAccessibleMock('AchimFritz\Documents\Domain\FileSystem\Service\ImageDocument\PdfExportService', array('foo'));
		$pdfExport = new PdfExport();
		$command = $pdfExportService->_call('getCommand', $pdfExport);
		$this->assertSame($defaultCommand, $command);
	}

}
