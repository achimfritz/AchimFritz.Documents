<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Mp3Document;

/**
 * Testcase for Mp3Document
 */
class Mp3DocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getFsTitleRemovesExtension() {
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('splitTrackTitle'));
		$document->expects($this->once())->method('splitTrackTitle')->will($this->returnValue(array(1, 'foo.mp3')));
		$fsTitle = $document->getFsTitle();
		$this->assertSame('foo', $fsTitle);
	}

}
