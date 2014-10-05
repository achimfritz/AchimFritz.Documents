<?php
namespace AchimFritz\Documents\Domain\Factory;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;
// TODO Domain\Model\Container
use AchimFritz\Documents\Domain\Solr\FacetContainer as Container;
use AchimFritz\Documents\Domain\Model\ContainerDiff;

/**
 * @Flow\Scope("singleton")
 */
class ContainerDiffFactory {

	/**
	 * create 
	 * 
	 * @param Container $first 
	 * @param Container $second 
	 * @return ArrayCollection<ContainerDiff>
	 */
	public function create(Container $first, Container $second) {
		$diffs = new ArrayCollection();

		$firstAssoc = $first->getAssoc();
		$secondAssoc = $second->getAssoc();
		$diff = array_diff_assoc($firstAssoc, $secondAssoc);
		foreach ($diff as $key => $val) {
			$containerDiff = new ContainerDiff();
			$containerDiff->setName($key);
			$containerDiff->setDiff($val);
			$cnt = 0;
			if (isset($firstAssoc[$key])) {
				$cnt = $firstAssoc[$key];
			}
			$containerDiff->setCountFirst($cnt);
			$cnt = 0;
			if (isset($secondAssoc[$key])) {
				$cnt = $secondAssoc[$key];
			}
			$containerDiff->setCountSecond($cnt);
			$diffs->add($containerDiff);
		}

		$diff = array_diff_assoc($secondAssoc, $firstAssoc);
		foreach ($diff as $key => $val) {
			$containerDiff = new ContainerDiff();
			$containerDiff->setName($key);
			$containerDiff->setDiff($val);
			$cnt = 0;
			if (isset($firstAssoc[$key])) {
				$cnt = $firstAssoc[$key];
			}
			$containerDiff->setCountFirst($cnt);
			$cnt = 0;
			if (isset($secondAssoc[$key])) {
				$cnt = $secondAssoc[$key];
			}
			$containerDiff->setCountSecond($cnt);
			$diffs->add($containerDiff);
		}

		return $diffs;
	}
	
}

?>
