<?php
namespace AchimFritz\Documents\Tests\Unit\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Linux\Command;

/**
 * Testcase for InputDocumentFactory
 */
class InputDocumentFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Linux\Exception
	 */
	public function readEyeD3TagsThrowsExceptionIfFileNotExists() {
		$command = $this->getMock('AchimFritz\Documents\Linux\Command', array('readEyeD3Tags'));
		$command->readId3Tags('not existing file');
	}

}
?>
