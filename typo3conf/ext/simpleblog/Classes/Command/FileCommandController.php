<?php
namespace Lobacher\Simpleblog\Command;
/**
 * Class FileCommandController
 *
 * Deletes files after a certain period of time
 */
class FileCommandController extends \TYPO3\CMS\Scheduler\Task
\AbstractTask
{
    public function execute()
    {
        // Fetch ObjectManager
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUti
        lity::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        // instantiate Repository
        $repository = $objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\FileRepository');
        // fetch configuration
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
        $settings = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $storagePid = $settings['plugin.']['tx_simpleblog.']['persistence.']['storagePid'];
        // set query settings (PID)
        $querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setStoragePageIds(array($storagePid));
        $repository->setDefaultQuerySettings($querySettings);
        // access Repository
        $files = $repository->findAll();
        // ... delete files ...
        return count($files);
    }
}