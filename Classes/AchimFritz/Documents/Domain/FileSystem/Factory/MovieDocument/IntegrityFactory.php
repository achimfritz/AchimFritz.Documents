<?php
namespace AchimFritz\Documents\Domain\FileSystem\Factory\MovieDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class IntegrityFactory extends \AchimFritz\Documents\Domain\FileSystem\Factory\IntegrityFactory {

   /**
    * @Flow\Inject
    * @var \AchimFritz\Documents\Domain\Repository\MovieDocumentRepository
    */
   protected $documentRepository;
}
