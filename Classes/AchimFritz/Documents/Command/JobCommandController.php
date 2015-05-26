<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Job;

/**
 * @Flow\Scope("singleton")
 */
class JobCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\JobRepository
	 */
	protected $jobRepository;

	/**
	 * @var \TYPO3\Flow\Persistence\Doctrine\PersistenceManager
	 * @Flow\Inject
	 */
	protected $persistenceManager;


	/**
	 * @return void
	 */
	public function listCommand() {
		$jobs = $this->jobRepository->findAll();
		if (count($jobs) === 0) {
			$this->outputLine('WARNING: no jobs found');
		}
		foreach ($jobs AS $job) {
			$this->outputLine($this->persistenceManager->getIdentifierByObject($job) . ' - ' . $job->getCommand());
		}
	}

	/**
	 * @param string $identifier
	 * @return void
	 */
	public function showCommand($identifier) {
		$job = $this->persistenceManager->getObjectByIdentifier($identifier, 'AchimFritz\Documents\Domain\Model\Job');
		if ($job instanceof Job === FALSE) {
			$this->outputLine('WARNING: no job found');
		}
		$this->outputLine($job->getCommand());
		$this->outputLine($job->getLog());
	}

	/**
	 * @return void
	 */
	public function runCommand() {
		$job = $this->jobRepository->findOneByStatus(Job::STATUS_RUNNING);
		if ($job instanceof Job === TRUE) {
			$this->quit();
		}
		$job = $this->jobRepository->findOneByStatus(Job::STATUS_WAITING);

		$descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "a")
		);

		if ($job instanceof Job === TRUE) {
			$job->setStatus(Job::STATUS_RUNNING);
			$job->setLog('');
			$job->setStartDate(new \DateTime());
			$this->jobRepository->update($job);
			$this->persistenceManager->persistAll();
			$process = proc_open($job->getCommand(), $descriptorspec, $pipes);

			if (is_resource($process)) {
				$log = '';
				while (!feof($pipes[1])) {
					$log .= fgets($pipes[1]);
					$job->setLog($log);
					$this->jobRepository->update($job);
					$this->persistenceManager->persistAll();
				}
				fclose($pipes[1]);
			}

			$returnValue = proc_close($process);
			$job->setReturnValue($returnValue);
			$job->setEndDate(new \DateTime());
			if ($returnValue === 0) {
				$job->setStatus(Job::STATUS_SUCCESS);
			} else {
				$job->setStatus(Job::STATUS_FAILED);
			}
			$this->jobRepository->update($job);
			$this->persistenceManager->persistAll();
		}
	}



}
