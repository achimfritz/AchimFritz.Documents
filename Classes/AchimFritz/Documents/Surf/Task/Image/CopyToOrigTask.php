<?php
namespace AchimFritz\Documents\Surf\Task\Image;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Surf".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Surf\Domain\Model\Node;
use TYPO3\Surf\Domain\Model\Application;
use TYPO3\Surf\Domain\Model\Deployment;

use TYPO3\Flow\Annotations as Flow;

/**
 * A task to create initial directories and the release directory for the current release
 *
 * This task will automatically create needed directories and create a symlink to the upcoming
 * release, called "next".
 */
class CopyToOrigTask extends Task {

	/**
	 * Executes this task
	 *
	 * @param \TYPO3\Surf\Domain\Model\Node $node
	 * @param \TYPO3\Surf\Domain\Model\Application $application
	 * @param \TYPO3\Surf\Domain\Model\Deployment $deployment
	 * @param array $options
	 * @return void
	 * @throws \TYPO3\Surf\Exception\TaskExecutionException
	 */
	public function execute(Node $node, Application $application, Deployment $deployment, array $options = array()) {
		$mountPoint = $this->configuration->getUsbMountPoint();
		$target = $this->configuration->getBackupPath() . '/' . $application->getTarget();
		$result = $this->shell->execute('test ! -d ' . $target, $node, $deployment, TRUE);
		if ($result === FALSE) {
			throw new \TYPO3\Surf\Exception\TaskExecutionException('Target directory "' . $target . '" already exist on node ' . $node->getName(), 1366541390);
		}

		if ($this->isLuckyHandy($application)) {
			$path = $this->configuration->getMountPoint() . '/*lucky_handy*/*';
			$commands = array(
				'k=`ls -tr ' . $path . '|tail -1`',
				'mkdir ' . $target,
				'find ' . $mountPoint . ' -newer $k -name "*.jpeg" |while read i; do cp -p $i ' . $target . '/; done',
				'find ' . $mountPoint . ' -newer $k -name "*.JPEG" |while read i; do cp -p $i ' . $target . '/; done',
				'find ' . $mountPoint . ' -newer $k -name "*.jpg" |while read i; do cp -p $i ' . $target . '/; done',
				'find ' . $mountPoint . ' -newer $k -name "*.JPG" |while read i; do cp -p $i ' . $target . '/; done'
			);
		} else {
			$commands = array(
				'mkdir ' . $target,
				'find ' . $mountPoint . ' -name "*.jpeg" |while read i; do cp -p $i ' . $target . '/; done',
				'find ' . $mountPoint . ' -name "*.JPEG" |while read i; do cp -p $i ' . $target . '/; done',
				'find ' . $mountPoint . ' -name "*.jpg" |while read i; do cp -p $i ' . $target . '/; done',
				'find ' . $mountPoint . ' -name "*.JPG" |while read i; do cp -p $i ' . $target . '/; done'
			);
		}

		
		$this->shell->executeOrSimulate($commands, $node, $deployment);
	}



	/**
	 * @param Application $application
	 * @return bool
	 */
	protected function isLuckyHandy(Application $application) {
		if (strpos($application->getTarget(), 'lucky_handy') !== FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}
