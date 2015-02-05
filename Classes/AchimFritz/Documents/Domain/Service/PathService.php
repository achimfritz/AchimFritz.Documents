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
	const HIERARCHICAL_PATHS = 'categories,locations';

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
		if ($this->isHierarchical($paths[0]) === TRUE) {
			return array(
				'name' => array_shift($paths),
				'values' => $this->getHierarchicalPaths(implode(self::PATH_DELIMITER, $paths))
			);
		} else {
			return array(
				'name' => $paths[0],
				'values' => array($paths[1])
			);
		}
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

	/**
	 * @return boolean
	 */
	protected function isHierarchical($name) {
		return in_array($name, explode(',', self::HIERARCHICAL_PATHS));
	}
}
