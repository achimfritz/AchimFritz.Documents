<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".*
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Facet\TvRecording;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class TvRecordingService {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @param TvRecording $tvRecording
	 * @return string
	 */
	public function at(TvRecording $tvRecording) {
		$recordFile = '/tmp/' . $tvRecording->getTitle() . '.sh';
		$outFile = '/data2/movies/' . $tvRecording->getTitle() . '.ts';
		$length = $tvRecording->getLength() * 60;
		$content = 'export HOME=/data/home/www-data && screen -D -m mplayer -quiet -dumpstream dvb://' . $tvRecording->getTvChannel()->getDecodedChannel() . ' -dumpfile ' . $outFile . ' & echo $! > /tmp/STREAM-record.pid
STREAM_PROCESS=$(cat /tmp/STREAM-record.pid)
sleep ' . $length . '
kill $STREAM_PROCESS
rm /tmp/STREAM-record.pid';
		if (file_put_contents($recordFile, $content) === FALSE) {
			throw new \AchimFritz\Documents\Linux\Exception('cannot write recordFile ' . $recordFile, 1441469434);
		}
		$cmd = 'at -f ' . $recordFile . ' ' . $tvRecording->getStarttime();
		$this->linuxCommand->executeCommand($cmd);
		return $cmd;
	}

}
