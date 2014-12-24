<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService;
use org\bovigo\vfs\vfsStream;

/**
 * Testcase for RenameService
 */
class DirectoryServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getSplFileInfosInDirectoryReturnsArrayOfSplFileInfos() {

		$structure = array(
			'images' => array(
				'0000_00_00_name' => array(
					'test.jpg' => 'img content',
					'foo.bar' => 'foo content'
				),
			)
		);

		$root = vfsStream::setup('root', NULL, $structure);
		$directoryService = new DirectoryService();
		$splFileObjects = $directoryService->getSplFileInfosInDirectory(vfsStream::url('root/images/0000_00_00_name'), 'jpg');
		$this->assertSame(1, count($splFileObjects));
		$first = $splFileObjects[0];
		$this->assertSame('test.jpg', $first->getBasename());
	}

}
