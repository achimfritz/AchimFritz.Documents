<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Category;

/**
 * Testcase for Category
 */
class CategoryTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * pathProvider 
	 * 
	 * @static
	 * @return array
	 */
	public static function pathProvider() {
		return array(
			array('search' => 'foo', 'replace' => 'bar', 'path' => 'foo/a', 'new' => 'bar/a'),
			array('search' => 'foo', 'replace' => 'bar', 'path' => 'a/foo', 'new' => 'a/foo'),
			array('search' => 'foo/a', 'replace' => 'bar/b', 'path' => 'foo/a', 'new' => 'bar/b'),
			array('search' => 'foo/1', 'replace' => 'bar', 'path' => '1/foo/1', 'new' => '1/foo/1'),
			array('search' => 'foo/bar', 'replace' => 'a/foo/bar', 'path' => 'foo/bar', 'new' => 'a/foo/bar'),
		);
	}

	/**
	 * @test
	 * @dataProvider pathProvider
	 */
	public function replacePathSetsPath($search, $replace, $path, $new) {
		$category = new Category();
		$category->setPath($path);
		$category->replacePath($search, $replace);
		$this->assertSame($new, $category->getPath());
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Model\Exception
	 */
	public function setPathThrowsExceptionForInvalidePath() {
		$category = new Category();
		$category->setPath('withoutSlash');
	}

	/**
	 * @test
	 */
	public function setPathSetsPath() {
		$category = new Category();
		$path = 'without/Slash';
		$category->setPath($path);
		$this->assertSame($path, $category->getPath());
	}
}
?>
