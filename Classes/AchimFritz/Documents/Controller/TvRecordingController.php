<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\TvRecording;

class TvRecordingController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\TvRecordingService
	 * @Flow\Inject
	 */
	protected $tvRecordService;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'tvRecording';


	/**
	 * @param \AchimFritz\Documents\Domain\Model\Facet\TvRecording $tvRecording
	 * @return void
	 */
	public function createAction(TvRecording $tvRecording) {
		try {
			$this->tvRecordService->at($tvRecording);
			$this->addFlashMessage('recoding added');
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->handleException($e);
		}
		$this->redirect('list', 'TvChannel');
	}

}
