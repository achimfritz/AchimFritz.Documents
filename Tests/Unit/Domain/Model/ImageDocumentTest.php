<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\FileSystemInterface;

/**
 * Testcase for ImageDocument
 */
class ImageDocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getMountPointReturnsFileSystemMountPoint() {
		$document = new ImageDocument();
		$this->assertSame(FileSystemInterface::IMAGE_MOUNT_POINT, $document->getMountPoint());
	}

}
?>
