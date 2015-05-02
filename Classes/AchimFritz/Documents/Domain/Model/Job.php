<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Job {

	const STATUS_WAITING = 1;
	const STATUS_RUNNING = 2;
	const STATUS_SUCCESS = 3;
	const STATUS_FAILED = 4;
	const STATUS_CANCELED = 5;

	/**
	 * @var integer
	 */
	protected $status;

	/**
	 * @var string
	 */
	protected $command;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $log = '';

	/**
	 * @var integer
	 */
	protected $returnValue = 0;

	/**
	 * @var \DateTime
	 */
	protected $createDate;

	/**
	 * @var \DateTime
	 */
	protected $startDate;

	/**
	 * @var \DateTime
	 */
	protected $endDate;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->createDate = new \DateTime();
		$this->startDate = new \DateTime();
		$this->endDate = new \DateTime();
		$this->status = self::STATUS_WAITING;
	}


	/**
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param integer $status
	 * @return void
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getCommand() {
		return $this->command;
	}

	/**
	 * @param string $command
	 * @return void
	 */
	public function setCommand($command) {
		$this->command = $command;
	}

	/**
	 * @return string
	 */
	public function getLog() {
		return $this->log;
	}

	/**
	 * @param string $log
	 * @return void
	 */
	public function setLog($log) {
		$this->log = $log;
	}

	/**
	 * @return integer
	 */
	public function getReturnValue() {
		return $this->returnValue;
	}

	/**
	 * @param integer $returnValue
	 * @return void
	 */
	public function setReturnValue($returnValue) {
		$this->returnValue = $returnValue;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreateDate() {
		return $this->createDate;
	}

	/**
	 * @param \DateTime $createDate
	 * @return void
	 */
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * @param \DateTime $startDate
	 * @return void
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 * @return void
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

}
