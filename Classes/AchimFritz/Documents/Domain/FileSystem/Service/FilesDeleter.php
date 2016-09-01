<?php
namespace AchimFritz\Documents\Domain\FileSystem\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class FilesDeleter {

	/**
	 * @param array $filePaths
	 * @return void
	 * @throws Exception
	 */
	public function delete(array $filePaths) {
		if (is_dir(FLOW_PATH_DATA . '/backup') === FALSE && mkdir(FLOW_PATH_DATA . '/backup/') === FALSE) {
			throw new Exception('no backup dir ' . FLOW_PATH_DATA . '/backup/', 1472712187);
		}
		// check writeable
		foreach ($filePaths as $filePath) {
			if (is_writable($filePath) === FALSE) {
				throw new Exception ('not writeable ' . $filePath, 1472712182);
			}
		}
		// backup
		$backups = array();
		foreach ($filePaths as $filePath) {
			$backup = str_replace('/', '____', $filePath);
			if (@rename($filePath, FLOW_PATH_DATA . '/backup/' . $backup) === FALSE) {
				// rollback
				foreach ($backups as $backup) {
					$origin = str_replace('____', '/', $backup);
					rename(FLOW_PATH_DATA . '/backup/' . $backup, $origin);
				}
				throw new Exception ('cannot move ' . $filePath, 1472712183);
			}
			$backups[] = $backup;
		}
		// unlink backups
		foreach ($backups as $backup) {
			unlink(FLOW_PATH_DATA . '/backup/' . $backup);
		}
	}


}
