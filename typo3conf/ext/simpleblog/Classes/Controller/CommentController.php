<?php

namespace Lobacher\Simpleblog\Controller;

class CommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * commentRepository
     *
     * @var \Lobacher\Simpleblog\Domain\Repository\CommentRepository
     * @inject
     */
    protected $commentRepository;

    public function initializeAction()
    {
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);
        $querySettings->setIgnoreEnableFields(TRUE);
        $querySettings->setEnableFieldsToBeIgnored(array('disabled'));
        $querySettings->setIncludeDeleted(TRUE);
        $this->commentRepository->setDefaultQuerySettings($querySettings);
    }

    public function listAction()
    {
        $this->view->assign('commentsLive', $this->commentRepository->findByDeleted(0));
        $this->view->assign('commentsDeleted', $this->commentRepository->findByDeleted(1));
    }

    /**
     * @param \Lobacher\Simpleblog\Domain\Model\Comment $comment
     */
    public function deleteAction(\Lobacher\Simpleblog\Domain\Model\Comment $comment)
    {
        $this->commentRepository->remove($comment);
        $this->redirect('list');
    }

    public function testAction()
    {
        return 'Output of testAction';
    }
}

?>