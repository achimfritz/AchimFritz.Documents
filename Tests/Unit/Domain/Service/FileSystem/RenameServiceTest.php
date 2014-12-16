<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\FileSystem\RenameService;

/**
 * Testcase for RenameService
 */
class RenameServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * @return array
	 */
	public static function nameProvider() {
		return array(
			array('name' => '01 - Stone Cold Sober (Radio Edit).mp3', 'expected' => '01-StoneColdSoberRadioEdit.mp3'),
			array('name' => '01 - Stone Cold Sober (Radio Edit).MP3', 'expected' => '01-StoneColdSoberRadioEdit.mp3'),
			array('name' => '01 - stoneCold Sober (Radio Edit).mp3', 'expected' => '01-StoneColdSoberRadioEdit.mp3'),
			array('name' => '01-StoneColdSoberRadioEdit.mp3', 'expected' => '01-StoneColdSoberRadioEdit.mp3'),
			array('name' => '01- Stone Cold Sober (Radio Edit).mp3', 'expected' => '01-StoneColdSoberRadioEdit.mp3'),
			array('name' => '01.jpg', 'expected' => '01.jpg'),
		);
	}

	/**
	 * @return array
	 */
	public static function pathProvider() {
		return array(
			array('name' => '/foo/bar/01 - Stone Cold Sober (Radio Edit).mp3', 'expected' => '/foo/bar/01-StoneColdSoberRadioEdit.mp3'),
			array('name' => '/Foo/01 - Stone Cold Sober (Radio Edit).MP3', 'expected' => '/Foo/01-StoneColdSoberRadioEdit.mp3'),
			array('name' => 'foo/01 - Stone Cold Sober (Radio Edit).MP3', 'expected' => 'foo/01-StoneColdSoberRadioEdit.mp3'),
		);
	}

	/**
	 * @test
	 * @dataProvider nameProvider
	 */
	public function getCleanNameReturnsCleanName($name, $expected) {
		$renameService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\RenameService', array('foo'));
		$renamed = $renameService->_call('getCleanName', $name);
		$this->assertSame($expected, $renamed);
	}

	/**
	 * @test
	 * @dataProvider pathProvider
	 */
	public function getCleanPathReturnsCleanPath($name, $expected) {
		$renameService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\RenameService', array('foo'));
		$renamed = $renameService->_call('getCleanPath', $name);
		$this->assertSame($expected, $renamed);
	}

}
