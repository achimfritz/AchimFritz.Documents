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
	 * @expectedException \AchimFritz\Documents\Domain\Model\Exception
	 */
	public function createThrowsExceptionIfFileNotExists() {	
		$factory = new FileSystemDocumentFactory();
		$document = $factory->create('foo');
	}

}
