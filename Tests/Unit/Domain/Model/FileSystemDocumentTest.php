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
	public function getAbsolutePathConcatsMountPointAndName() {	
		$document = new FileSystemDocument();
		$document->setMountPoint('foo');
		$document->setName('bar');
		$this->assertSame('foo/bar', $document->getAbsolutePath());
	}

}
?>
