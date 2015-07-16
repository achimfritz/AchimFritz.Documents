<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Job;

class JobController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\JobRepository
	 */
	protected $jobRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'job';

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('jobs', $this->jobRepository->findAll());
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Job $job
	 * @return void
	 */
	public function showAction(Job $job) {
		$this->view->assign('job', $job);
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Job $job
	 * @return void
	 */
	public function createAction(Job $job) {
		$this->jobRepository->add($job);
		$this->addFlashMessage('Created a new job.');
		$this->redirect('index', NULL, NULL, array('job' => $job));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Job $job
	 * @return void
	 */
	public function updateAction(Job $job) {
		$this->jobRepository->update($job);
		$this->addFlashMessage('Updated the job.');
		$this->redirect('index', NULL, NULL, array('job' => $job));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Job $job
	 * @return void
	 */
	public function deleteAction(Job $job) {
		$this->jobRepository->remove($job);
		$this->addFlashMessage('Deleted a job.');
		$this->redirect('index');
	}

}
