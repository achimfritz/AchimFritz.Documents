<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\Image;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ExportService {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 * @Flow\Inject
	 */
	protected $pathService;

	/**
	 * @param string $directory
	 * @param \Doctrine\Common\Collections\Collection<> $documents 
	 * @return void
	 */
	public function run($directory, \Doctrine\Common\Collections\Collection $documents) {
		if (@file_exists(directory) === TRUE) {
			throw new Exception('file exists ' . $directory, 1416762866);
		}
		if (@mkdir($directory) === FALSE) {
			throw new Exception('cannot create directory ' . $directory, 1416762867);
		}
		foreach ($documents as $document) {
			$from = $document->getAbsolutePath();
			// TODO
			$from = $this->pathService->replacePath($from, $document->getMountPoint(), '/bilder/thumbs/1280x1024');
			$to = $directory . '/' . $document->getSplFileInfo()->getBaseName();
			if (@copy($from, $to) === FALSE) {
				throw new Exception('cannot copy document ' . $document->getAbsolutePath() , 1416762868);
			}
		}

	}


}
