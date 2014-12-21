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
	public function getDirectoryNameReturnsFirstPart() {
		$document = new ImageDocument();
		$document->setName('foo/bar');
		$this->assertSame('foo', $document->getDirectoryName());
	}

}
?>
