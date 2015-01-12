<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\ImageDocument;

/**
 * Testcase for ImageDocument
 */
class ImageDocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getWebPreviewPathReturnsWebPreviewPathAndName() {
		$configuration = $this->getMock('AchimFritz\Documents\Configuration\ImageDocumentConfiguration', array('getWebPreviewPath'));
		$configuration->expects($this->once())->method('getWebPreviewPath')->will($this->returnValue('foo'));
		$document = new ImageDocument();
		$this->inject($document, 'imageDocumentConfiguration', $configuration);
		$document->setName('bar/file.txt');
		$this->assertSame('foo/bar/file.txt', $document->getWebPreviewPath());
	}


	/**
	 * @test
	 */
	public function getWebBigPathReturnsWebBigPathAndName() {
		$configuration = $this->getMock('AchimFritz\Documents\Configuration\ImageDocumentConfiguration', array('getWebBigPath'));
		$configuration->expects($this->once())->method('getWebBigPath')->will($this->returnValue('foo'));
		$document = new ImageDocument();
		$this->inject($document, 'imageDocumentConfiguration', $configuration);
		$document->setName('bar/file.txt');
		$this->assertSame('foo/bar/file.txt', $document->getWebBigPath());
	}

	/**
	 * @test
	 */
	public function getWebThumbPathReturnsWebThumbPathAndName() {
		$configuration = $this->getMock('AchimFritz\Documents\Configuration\ImageDocumentConfiguration', array('getWebThumbPath'));
		$configuration->expects($this->once())->method('getWebThumbPath')->will($this->returnValue('foo'));
		$document = new ImageDocument();
		$this->inject($document, 'imageDocumentConfiguration', $configuration);
		$document->setName('bar/file.txt');
		$this->assertSame('foo/bar/file.txt', $document->getWebThumbPath());
	}
}
