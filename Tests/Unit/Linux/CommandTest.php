<?php
namespace AchimFritz\Documents\Tests\Unit\Linux;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Linux\Command;
use org\bovigo\vfs\vfsStream;

/**
 * Testcase for InputDocumentFactory
 */
class CommandTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Linux\Exception
	 */
	public function readId3TagsThrowsExceptionIfFileNotExists() {
		$command = new Command();
		$command->readId3Tags('not existing file');
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Linux\Exception
	 */
	public function writeId3TagsThrowsExceptionIfFileNotExists() {
		$command = new Command();
		$command->writeId3Tag('not existing file', 'foo', 'bar');
	}

	/**
	 * @test
	 */
	public function writeId3TagsCallsEyeD3() {
		$root = vfsStream::setup('root', NULL, array('tmp' => array('fileName' => 'x')));
		$command = $this->getMock('AchimFritz\Documents\Linux\Command', array('executeCommand'));
		$command->expects($this->once())->method('executeCommand')->with('eyeD3 --foo="bar" ' . vfsStream::url('root/tmp/fileName'));
		$command->writeId3Tag(vfsStream::url('root/tmp/fileName'), 'foo', 'bar');
	}

}
