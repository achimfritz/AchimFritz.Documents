<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService;
use org\bovigo\vfs\vfsStream;

/**
 * Testcase for DirectoryService
 */
class DirectoryServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \org\bovigo\vfs\vfsStreamDirectory
	 */
	protected $root;

	/**
	 * @return void
	 */
	public function setUp() {
		$structure = array(
			'images' => array(
				'0000_00_00_name' => array(
					'test.jpg' => 'img content',
					'foo.bar' => 'foo content'
				),
			)
		);

		$this->root = vfsStream::setup('root', NULL, $structure);
	}

	/**
	 * @test
	 */
	public function getSplFileInfosInDirectoryReturnsArrayOfSplFileInfos() {
		$directoryService = new DirectoryService();
		$splFileObjects = $directoryService->getSplFileInfosInDirectory(vfsStream::url('root/images/0000_00_00_name'), 'jpg');
		$this->assertSame(1, count($splFileObjects));
		$first = $splFileObjects[0];
		$this->assertSame('test.jpg', $first->getBasename());
	}

	/**
	 * @test
	 */
	public function getFileNamesInDirectoryReturnsArrayOfFileNames() {
		$directoryService = $this->getMock('AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService', array('getSplFileInfosInDirectory'));
		$splFileInfo = new \SplFileInfo(vfsStream::url('root/images/0000_00_00_name/test.jpg'));
		$directoryService->expects($this->once())->method('getSplFileInfosInDirectory')->will($this->returnValue(array($splFileInfo)));
		$fileNames = $directoryService->getFileNamesInDirectory(vfsStream::url('root/images/0000_00_00_name'), 'jpg');
		$this->assertSame(1, count($fileNames));
		$first = $fileNames[0];
		$this->assertSame('test.jpg', $first);
	}

	/**
	 * @test
	 */
	public function getCountOfFilesByExtensionReturnsInteger() {
		$directoryService = new DirectoryService();
		$cnt = $directoryService->getCountOfFilesByExtension(vfsStream::url('root/images/0000_00_00_name'), 'jpg');
		$this->assertSame(1, $cnt);
	}

}
