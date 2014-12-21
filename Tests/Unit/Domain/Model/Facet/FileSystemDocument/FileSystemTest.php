<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\FileSystemDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\FileSystem;

/**
 * Testcase for InputDocumentFactory
 */
class FileSystemTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getAbsolutePathReturnsMountPointAndRelativePath() {
		$fileSystem = new FileSystem();
		$fileSystem->setRelativePath('foo/bar');
		$fileSystem->setMountPoint('/baz');
		$this->assertSame('/baz/foo/bar', $fileSystem->getAbsolutePath());
	}

}
