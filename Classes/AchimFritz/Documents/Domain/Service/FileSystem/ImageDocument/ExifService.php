<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemProperty;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
class ExifService {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @param FileSystemProperty
	 * @throws Exception
	 * @return array
	 */
	public function getCorrectCommands(FileSystemProperty $fileSystemProperty) {
		$commands = array();
		$absolutePath = $fileSystemProperty->getAbsolutePath();
		if ($fileSystemProperty->hasExifData() === FALSE) {
			$commands[] = 'exif -c ' . $absolutePath;
			$commands[] = 'mv ' . $absolutePath . '.modified.jpeg ' . $absolutePath;
			// default orientation
			$commands[] = 'exiftool -ExifImageHeight=' . $fileSystemProperty->getHeight() . ' -ExifImageWidth=' . $fileSystemProperty->getWidth() . ' ' . $absolutePath;
			$commands[] = 'exiftool -Orientation=' . FileSystemProperty::ORIENTATION_0 . ' -n ' . $absolutePath;
		}
		$transform = FALSE;
		$orientation = $fileSystemProperty->getGeeqieOrientation();
		if ($orientation > 0) {
			$commands[] = 'exiftool -Orientation=' . $orientation . ' -n ' . $absolutePath;
			$transform = TRUE;
		}
		if (
			$transform === TRUE 
			|| (
				$fileSystemProperty->hasExifData() === TRUE
				&& $fileSystemProperty->getExifOrientation() !== FileSystemProperty::EXIF_ORIENTATION_0
			)
		) {
				$commands[] = 'exiftran -ai ' . $absolutePath;
		}
		$commands[] = 'touch -t ' . $fileSystemProperty->getTimestamp() . ' ' . $absolutePath;
		$origFile = $absolutePath . '_original';
		$commands[] = 'if [ -e ' . $origFile . ' ]; then rm ' . $origFile . '; fi';
		return $commands;
	}


}
