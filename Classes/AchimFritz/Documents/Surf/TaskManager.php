<?php
namespace AchimFritz\Documents\Surf;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents\Surf".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A Image one workflow
 *
 * @Flow\Entity
 */
class TaskManager extends \TYPO3\Surf\Domain\Service\TaskManager {

	/**
	 * @param string $taskName
	 * @return \TYPO3\Surf\Domain\Model\Task
	 * @throws \TYPO3\Surf\Exception\InvalidConfigurationException
	 */
	protected function createTaskInstance($taskName) {
		list($packageKey, $taskName) = explode(':', $taskName, 2);
		$taskClassName = strtr($packageKey, '.', '\\') . '\\Surf\\Task\\' . strtr($taskName, ':', '\\') . 'Task';
		$taskObjectName = $this->objectManager->getCaseSensitiveObjectName($taskClassName);
		if (!$this->objectManager->isRegistered($taskObjectName)) {
			throw new \TYPO3\Surf\Exception\InvalidConfigurationException('Task "' . $taskName . '" was not registered (class "' . $taskClassName . '" not found)', 1335976651);
		}
		$task = new $taskObjectName();
		return $task;
	}



}
