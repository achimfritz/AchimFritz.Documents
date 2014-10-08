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

	/**
	 * splitPath 
	 *
	 * @param string $path
	 * @return array
	 */
	protected function splitPath($path) {
		return explode(self::PATH_DELIMITER, $path);
	}

	/**
	 * getHierarchicalPaths
	 *
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

}
?>
