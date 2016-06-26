<?php
namespace Arrabiata\PdfGenerator\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Pdf generator Service
 *
 * @package Arrabiata\PdfGenerator\Service
 */
class PdfGenerator extends \TYPO3\CMS\Core\Service\AbstractService {

	/**
	 * Returns instance of basic class 'PdfGenerator'
	 *
	 * @return \PdfGenerator
	 */
	public function getPdfGenerator($template = 'MakePdf') {
		return $this->getInstanceOf($template);
	}

	/**
	 * Creates and returns instance of given class name.
	 *
	 * @param string $className
	 * @return object
	 */
	public function getInstanceOf($className) {
		if (func_num_args() > 1) {
			$constructorArguments = func_get_args();
			array_shift($constructorArguments);

			$reflectedClass = new \ReflectionClass($className);
			$instance = $reflectedClass->newInstanceArgs($constructorArguments);
		} else {
			$instance = GeneralUtility::makeInstance($className);
		}
		return $instance;
	}
}