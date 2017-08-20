<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\MovieDocument as Document;

/**
 * @Flow\Scope("singleton")
 */
class MovieDocumentCommandController extends FileSystemDocumentCommandController {


	/**
	 * @var \AchimFritz\Documents\Domain\Repository\MovieDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;
		
	/**
	 * @var \AchimFritz\Documents\Domain\Factory\MovieDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Factory\MovieDocument\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\MovieIndexService
	 * @Flow\Inject
	 */
	protected $indexService;

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;


	/**
	 * show --name=2006_10_23_roland_scan_roland_gisela/fritzam_digcam_hochzeit_britta_dsci0048.jpg
	 *
	 * @param string $name
	 * @return void
	 */
	public function showCommand($name) {
		$document = $this->documentRepository->findOneByName($name);
		if ($document instanceof Document) {
			$this->outputLine($document->getName() . ' - ' . $document->getFileHash());
			$json = $document->getFfmpeg();
			var_dump($json);
			$this->outputLine('has ' . count($json['streams']) . ' streams');
			foreach($json['streams'] as $stream) {
				$this->outputLine('codec: ' . $stream['codec_type'] . ' ' . $stream['codec_name']);
			}
		} else {
			$this->outputLine('WARNING: no document found ' . $name);
		}
	}

	public function importCommand() {
		$mount = '/mnt/sdc1';
		$content = file_get_contents($mount . '/movies_ext/save/out.csv');
		$lines = explode("\n", $content);
		foreach ($lines as $line) {
			$arr = explode(';', $line);
			if (count($arr) > 2) {
				$name = array_shift($arr);
				$this->outputLine();
				$this->outputLine($name);
				$orientation = '';
				$vdrOptions = [];
				foreach ($arr as $vdr) {
					if (trim($vdr) !== '') {
						try {
							$ffmpeg = $this->linuxCommand->movieInfo($mount . '/movies_ext/save/' . $vdr);
							$audioChannel = '';
							foreach ($ffmpeg->streams as $stream) {
								if ($stream->codec_type === 'video') {
									// check orientation
									if ($orientation === '') {
										$orientation = $stream->display_aspect_ratio;
									} else {
										if ($orientation !== $stream->display_aspect_ratio) {
											#throw new \Exception('orientation missmatch ' . $orientation . ' - ' . $stream->display_aspect_ratio, 1492619791);
										}
									}
								}
								if ($stream->codec_type === 'audio' && ($stream->codec_name === 'mp2' || $stream->codec_name === 'mp3') && $stream->channel_layout === 'stereo') {
									if ($audioChannel !== '') {
										throw new \Exception('more than one audio stereo stream', 1492619792);
									} else {
										$audioChannel = $stream->index;
									}
								}
							}
							$vdrOption = [
								'file' => $mount . '/movies_ext/save/' . $vdr,
								'audioChannel' => $audioChannel
							];
							$vdrOptions[] = $vdrOption;
							// check which audio channel is mp2 
							// check only 2 audio channels (otherwise one may be "with subtitles")
						} catch (\Exception $e) {
							$this->outputLine('ERROR ' . $e->getMessage());
							$this->quit();
						}
					}
				}

				if ($orientation === '' || $audioChannel === '') {
					$this->outputLine('ERROR: no orientation or audioChannel');
				} else {
					$content = [];
					/*
					ffmpeg -i 001.vdr -i 002.vdr -i 003.vdr \
					-filter_complex '[0:0] [0:1] [1:0] [1:1] [2:0] [2:3] concat=n=3:v=1:a=1 [v] [a]' \
					-map '[v]' -map '[a]' Der_Baader_Meinhof_Komplex_1.mp4
					*/
					$content[] = 'ffmpeg';
					$cnt = 0;
					$filters = [];
					foreach ($vdrOptions as $vdrOption) {
						$content[] = '-i ' . $vdrOption['file'];
						$filters[] = '[' . $cnt . ':0] [' . $cnt . ':' . $vdrOption['audioChannel'] . ']';
						$cnt++;
					}
					$content[] = '-filter_complex';
					$content[] = '\'' . implode(' ', $filters);
					$content[] = 'concat=n=' . $cnt . ':v=1:a=1 [v] [a]\'  -map \'[v]\' -map \'[a]\'';
					$content[] = $mount . '/movies_ext/n2/' . $name . '.mp4';
					$this->outputLine(implode(' ', $content));

				}
			}
		}
	}

}
