<?php
namespace Lobacher\Simpleblog\Validation\Validator;
class WordValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    protected $supportedOptions = array(
        'max' => array(PHP_INT_MAX, 'The maximum word count to accept', 'integer'),
    );

    public function isValid($property)
    {
        $max = $this->options['max'];
        if (str_word_count($property, 0) <= $max) {
            return TRUE;
        } else {
            $this->addError('Reduce the amount of words - max ' . $max . ' are allowed!', 1383400016);
            return FALSE;
        }
    }

}

?>