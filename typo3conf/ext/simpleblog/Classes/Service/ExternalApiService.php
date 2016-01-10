<?php
namespace Lobacher\Simpleblog\Service;
class ExternalApiService implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @param \Lobacher\Simpleblog\Domain\Model\blog $blog
     * @return array
     */
    public function validateData(\Lobacher\Simpleblog\Domain\Model\blog $blog)
    {
        $errors = array();
        $fb = file_get_contents('http://graph.facebook.com/' . preg_replace('/\s+/', '', $blog->getTitle()), false,
            stream_context_create(
                array(
                    'http' => array(
                        'ignore_errors' => true
                    )
                )
            ));
        $fb = json_decode($fb, true);
        if (!$fb['error']) {
            $errors['title'] = 'Titel exists as an user at Facebook (ID = ' . $fb['id'] . ' / URL = ' . $fb['link'] . ')!';
        }

        return $errors;
    }
}

?>