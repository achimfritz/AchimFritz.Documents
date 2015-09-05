<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\TvChannel;
use AchimFritz\Documents\Linux\Exception as LinuxCommandException;
use AchimFritz\Documents\Domain\Model\Facet\TvRecording;

/**
 * @Flow\Scope("singleton")
 */
class TvChannelCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\TvChannelRepository
	 */
	protected $tvChannelRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\TvRecordingService
	 * @Flow\Inject
	 */
	protected $tvRecordService;

	/**
	 * @return void
	 */
	public function listCommand() {
		$tvChannels = $this->tvChannelRepository->findAll();
		foreach ($tvChannels as $tvChannel) {
			$this->outputLine($tvChannel->getName() . ': ' . $tvChannel->getDecodedChannel());
		}
	}

	/**
	 * @param string $name
	 * @param integer $length
	 * @param string $out
	 * @param string $start
	 * @return void
	 */
	public function recordCommand($name, $length, $out, $start) {
		$tvChannel = $this->tvChannelRepository->findOneByName($name);
		if ($tvChannel instanceof TvChannel === FALSE) {
			$this->outputLine('ERROR: no such channel ' . $name);
			$this->quit();
		}
		$tvRecording = new TvRecording();
		$tvRecording->setLength($length);
		$tvRecording->setStarttime($start);
		$tvRecording->setTvChannel($tvChannel);
		$tvRecording->setTitle($out);
		try {
			$this->tvRecordService->at($tvRecording);
			$this->outputLine('SUCESS');
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
		}

		$recordFile = '/tmp/' . $out . '.sh';
		$outFile = '/data2/movies/' . $out . '.ts';
		$length = $length * 60;
		$content = 'screen -D -m mplayer -quiet -dumpstream dvb://' . $tvChannel->getDecodedChannel() . ' -dumpfile ' . $outFile . ' & echo $! > /tmp/STREAM-record.pid
STREAM_PROCESS=$(cat /tmp/STREAM-record.pid)
sleep ' . $length . '
kill $STREAM_PROCESS
rm /tmp/STREAM-record.pid';
		if (file_put_contents($recordFile, $content) === FALSE) {
			$this->outputLine('ERROR: cannot write recordFile ' . $recordFile);
			$this->quit();
		}
		$cmd = 'at -f ' . $recordFile . ' ' . $start;
		try {
			$this->linuxCommand->executeCommand($cmd);
		} catch (LinuxCommandException $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
		}

	}

}
