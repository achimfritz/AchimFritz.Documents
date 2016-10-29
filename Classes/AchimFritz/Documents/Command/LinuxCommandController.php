<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
class LinuxCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $command;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\DirectoryService
	 * @Flow\Inject
	 */
	protected $directoryService;

	/**
	 * burnDvdIso --isoFile=/tmp/image.iso
	 *
	 * @param string $isoFile
	 * @return void
	 */
	public function burnDvdIsoCommand($isoFile) {
		try {
			$this->command->burnIsoImageDvd($isoFile);
			$this->outputLine('SUCCESS: image burned');
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * burndatacd --directoryName=/mp3/tmp
	 *
	 * @param string $directoryName
	 * @return void
	 */
	public function burnDataCdCommand($directoryName) {
		try {
			$this->command->burnDataCd($directoryName);
			$this->outputLine('SUCCESS: image created');
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * burnwavcd --directoryName=/mp3/tmp
	 *
	 * @param string $directoryName
	 * @return void
	 */
	public function burnWavCdCommand($directoryName) {
		try {
			$this->command->burnWavCd($directoryName);
			$this->outputLine('SUCCESS: image created');
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * mp3towav --directoryName=/mp3/tmp
	 *
	 * @param string $directoryName
	 * @return void
	 */
	public function mp3ToWavCommand($directoryName) {
		try {
			$files = $this->directoryService->getSplFileInfosInDirectory($directoryName, 'mp3');
			foreach ($files AS $file) {
				$info = $file->getPathInfo();
				$directory = $info->getRealPath();
				$wav = $directory . PathService::PATH_DELIMITER . $file->getBasename('.mp3') . '.wav';
				try {
					$this->command->mp3ToWave($file->getRealPath(), $wav);
				} catch (\AchimFritz\Documents\Linux\Exception $e) {
					$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
				}
			}
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * wavtomp3 --directoryName=/mp3/tmp
	 *
	 * @param string $directoryName
	 * @return void
	 */
	public function wavToMp3Command($directoryName) {
		try {
			$files = $this->directoryService->getSplFileInfosInDirectory($directoryName, 'wav');
			foreach ($files AS $file) {
				$info = $file->getPathInfo();
				$directory = $info->getRealPath();
				$mp3 = $directory . PathService::PATH_DELIMITER . $file->getBasename('.wav') . '.mp3';
				try {
					$this->command->wavToMp3($file->getRealPath(), $mp3);
				} catch (\AchimFritz\Documents\Linux\Exception $e) {
					$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
				}
			}
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

}
