<?php
namespace Lobacher\Simpleblog\Validation\Validator;
class FacebookValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * API Service
     *
     * @var \Lobacher\Simpleblog\Service\ExternalApiService
     * @inject
     */
    protected $apiService;
    /**
     * Object Manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;

    /**
     * Validates the given value
     *
     * @param mixed $value
     * @return bool
     */
    protected function isValid($value)
    {
        $apiValidationResult = $this->apiService->validateData($value);
        $success = TRUE;
        if ($apiValidationResult['title']) {
            $error = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Validation\\Error', $apiValidationResult['title'], 1389545453);
            $this->result->forProperty('title')->addError($error);
            $success = FALSE;
        }
        return $success;
    }
}

?>