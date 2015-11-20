<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\FileSystem\Facet\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\PdfExport;

/**
 * Testcase for FileSystemDocument
 */
class PdfExportTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getPaperWidthReturnsDefaultWidthForA4() {
		$document = $this->getAccessibleMock('AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\PdfExport', array('foo'));
		$paperWidth = $document->_call('getPaperWidth');
		$this->assertSame(2480, $paperWidth);
	}

	/**
	 * @test
	 */
	public function getRowsReturnsDefaultRows() {
		$document = new PdfExport();
		$rows = $document->getRows();
		$this->assertSame(5, $rows);
	}

}
