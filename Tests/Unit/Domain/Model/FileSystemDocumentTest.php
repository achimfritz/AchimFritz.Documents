<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\FileSystemDocument;
use AchimFritz\Documents\Domain\FileSystemInterface;

/**
 * Testcase for FileSystemDocument
 */
class FileSystemDocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getAbsolutePathConcatsMountPointAndName() {	
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\FileSystemDocument', array('getMountPoint'));
		$document->expects($this->once())->method('getMountPoint')->will($this->returnValue('/foo'));
		$document->setName('bar');
		$this->assertSame('/foo/bar', $document->getAbsolutePath());
	}

	/**
	 * @test
	 */
	public function getMountPointReturnsFileSystemMountPoint() {
		$document = new FileSystemDocument();
		$this->assertSame(FileSystemInterface::MOUNT_POINT, $document->getMountPoint());
	}

	/**
	 * @test
	 */
	 public function getFileNameReturnsSplFileInfoBaseName() {
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\FileSystemDocument', array('getAbsolutePath'));
		$document->expects($this->once())->method('getAbsolutePath')->will($this->returnValue('/foo/bar.ext'));
		$this->assertSame('bar.ext', $document->getFileName());
	 }

	/**
	 * @test
	 */
	 public function getDirectoryNameReturnsSplFileInfoDirName() {
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\FileSystemDocument', array('getAbsolutePath'));
		$document->expects($this->once())->method('getAbsolutePath')->will($this->returnValue('/foo/bar.ext'));
		$this->assertSame('foo', $document->getDirectoryName());
	 }
}
