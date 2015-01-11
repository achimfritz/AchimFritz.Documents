<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\FileSystemDocument;

/**
 * Testcase for FileSystemDocument
 */
class FileSystemDocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getDirectoryNameReturnsFirstPart() {
		$splFileInfo = new \SplFileInfo('/foo/bar/file.jpg');
		$document = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\ImageDocument', array('foo'));
		$document->_set('splFileInfo', $splFileInfo);
		$document->setName('bar/file.jpg');
		$this->assertSame('bar', $document->getDirectoryName());
	}

	/**
	 * @test
	 */
	public function getAbsolutePathReturnsMountPointAndName() {
		$configuration = $this->getMock('AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration', array('getMountPoint'));
		$configuration->expects($this->once())->method('getMountPoint')->will($this->returnValue('/foo'));
		$document = new FileSystemDocument();
		$this->inject($document, 'configuration', $configuration);
		$document->setName('bar/file.txt');
		$this->assertSame('/foo/bar/file.txt', $document->getAbsolutePath());
	}

	/**
	 * @test
	 */
	public function getWebPathReturnsWebPathAndName() {
		$configuration = $this->getMock('AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration', array('getWebPath'));
		$configuration->expects($this->once())->method('getWebPath')->will($this->returnValue('foo'));
		$document = new FileSystemDocument();
		$this->inject($document, 'configuration', $configuration);
		$document->setName('bar/file.txt');
		$this->assertSame('foo/bar/file.txt', $document->getWebPath());
	}
}
