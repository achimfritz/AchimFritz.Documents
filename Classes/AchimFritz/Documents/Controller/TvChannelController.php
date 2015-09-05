<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\TvChannel;

class TvChannelController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\TvChannelRepository
	 */
	protected $tvChannelRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'tvChannel';

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('tvChannels', $this->tvChannelRepository->findAll());
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\TvChannel $tvChannel
	 * @return void
	 */
	public function showAction(TvChannel $tvChannel) {
		$this->view->assign('tvChannel', $tvChannel);
		$this->view->assign('tvChannels', $this->tvChannelRepository->findAll());
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\TvChannel $tvChannel
	 * @return void
	 */
	public function createAction(TvChannel $tvChannel) {
		$this->tvChannelRepository->add($tvChannel);
		$this->addFlashMessage('Created a new tv channel.');
		$this->redirect('list');
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\TvChannel $tvChannel
	 * @return void
	 */
	public function updateAction(TvChannel $tvChannel) {
		$this->tvChannelRepository->update($tvChannel);
		$this->addFlashMessage('Updated the tv channel.');
		$this->redirect('list');
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\TvChannel $tvChannel
	 * @return void
	 */
	public function deleteAction(TvChannel $tvChannel) {
		$this->tvChannelRepository->remove($tvChannel);
		$this->addFlashMessage('Deleted a tv channel.');
		$this->redirect('list');
	}

}
