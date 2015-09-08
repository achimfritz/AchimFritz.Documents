<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Factory\FileSystemDocumentFactory;

/**
 * Testcase for FileSystemDocument
 */
class FileSystemDocumentFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Factory\Exception
	 */
	public function createThrowsExceptionIfFileNotExists() {
		$factory = new FileSystemDocumentFactory();
		$factory->create('foo');
	}

}
