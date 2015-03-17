<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class PathService {

	const PATH_DELIMITER = '/';
	const TEMP_FOLDER = '/tmp';

	/**
	 * @param string $path
	 * @return array
	 */
	public function splitPath($path) {
		return explode(self::PATH_DELIMITER, $path);
	}

	/**
	 * @param string $path 
	 * @return integer
	 */
	public function getPathDepth($path) {
		return count($this->splitPath($path));
	}

	/**
	 * /foo/bar/baz
	 *
	 * foo
	 * foo/bar
	 * foo/bar/baz
	 *
	 * @param string $path
	 * @return array
	 */
	public function getPaths($path) {
		$hierarchicalPaths = array();
		$paths = $this->splitPath($path);
		$hierarchicalPath = '';
		$k = 0;
		foreach ($paths AS $path) {
			if ($k != 0) {
				$hierarchicalPath .= self::PATH_DELIMITER;
			}
			$hierarchicalPath .= $path;
			$hierarchicalPaths[] = $hierarchicalPath;
			$k++;
		}
		return $hierarchicalPaths;
	}

	/**
	 * /foo/bar/baz
	 *
	 * 0/foo
	 * 1/foo/bar
	 * 2/foo/bar/baz
	 *
	 * @param string $path
	 * @return array
	 */
	public function getHierarchicalPaths($path) {
		$hierarchicalPaths = array();
		$paths = $this->splitPath($path);
		$hierarchicalPath = '';
		$k = 0;
		foreach ($paths AS $path) {
			if ($k != 0) {
				$hierarchicalPath .= self::PATH_DELIMITER;
			}
			$hierarchicalPath .= $path;
			$hierarchicalPaths[] = $k . self::PATH_DELIMITER . $hierarchicalPath;
			$k++;
		}
		return $hierarchicalPaths;
	}

	/**
	 * @param string $path 
	 * @return array
	 */
	public function getSolrField($path) {
		$hierarchicalPaths = array();
		$paths = $this->splitPath($path);
		return array(
			'name' => array_shift($paths),
			'value' => implode(self::PATH_DELIMITER, $paths)
		);
	}

	/**
	 * @param string $path
	 * @param string $search 
	 * @param string $replace 
	 * @return string $path
	 */
	public function replacePath($path, $search, $replace) {
		$search = preg_quote($search, self::PATH_DELIMITER);
		$path = preg_replace('/^' . $search . '/', $replace, $path);
		return $path;
	}

}
