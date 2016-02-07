<?php
namespace Lobacher\Simpleblog\Validation\Validator;

class TermsAndConditionsValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**#
     * This contains the supported options, their default values,
     * descriptions and types.
     *
     * @var array
     */
    protected $supportedOptions = array(
        'value' => array(
            0,
            'The terms and condition checked',
            'integer'
        )
    );

    /**
     * Validates the given value
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        // Override the value
        if (!empty($this->options['value'])) {
            $value = $this->options['value'];
        }

        // Need your own validation checking here
        if ($value !== '1') {
            $error = new \TYPO3\CMS\Extbase\Validation\Error('ERROR', time());
            $this->result->forProperty('acceptTermsAndConditions')->addError($error);
            return FALSE;
        }
        return TRUE;
    }
}

?>