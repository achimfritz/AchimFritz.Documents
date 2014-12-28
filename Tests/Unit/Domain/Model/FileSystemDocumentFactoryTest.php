<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\FileSystemDocumentFactory;

/**
 * Testcase for FileSystemDocument
 */
class FileSystemDocumentFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function createSetsName() {	
		$factory = new FileSystemDocumentFactory();
		$document = $factory->create('foo');
		$this->assertSame('foo', $document->getName());
	}

}
?>
