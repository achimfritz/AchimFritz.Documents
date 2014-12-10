<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\PathService;

/**
 * Testcase for PathService
 */
class PathServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * @return array
	 */
	public static function replaceProvider() {
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
	 * @dataProvider replaceProvider
	 */
	public function replacePathReplacePath($search, $replace, $path, $new) {
		$pathService = new PathService();
		$replaced = $pathService->replacePath($path, $search, $replace);
		$this->assertSame($new, $replaced);
	}

	/**
	 * @test
	 */
	public function getHierarchicalPathsReturnsArray() {
		$pathService = new PathService();
		$path = 'foo/bar/baz';
		$expected = array(
			'0/foo',
			'1/foo/bar',
			'2/foo/bar/baz'
		);
		$res = $pathService->getHierarchicalPaths($path);
		$this->assertSame($expected, $res);
	}

	/**
	 * @test
	 */
	public function getSolrFieldReturnsSingleValueForPathDepthTwo() {
		$pathService = new PathService();
		$path = 'foo/bar';
		$expected = array(
			'name' => 'foo',
			'values' => array('bar')
		);
		$res = $pathService->getSolrField($path);
		$this->assertSame($expected, $res);
	}

	/**
	 * @test
	 */
	public function getSolrFieldsReturnMultiValuesForPathDepthThree() {
		$pathService = new PathService();
		$path = 'foo/bar/baz';
		$expected = array(
			'name' => 'foo',
			'values' => array('0/bar', '1/bar/baz')
		);
		$res = $pathService->getSolrField($path);
		$this->assertSame($expected, $res);
	}



}
?>
