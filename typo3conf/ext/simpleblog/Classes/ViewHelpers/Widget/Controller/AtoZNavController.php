<?php
namespace Lobacher\Simpleblog\ViewHelpers\Widget\Controller;

class AtoZNavController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected $objects;

    public function initializeAction()
    {
        $this->objects = $this->widgetConfiguration['objects'];
    }

    /**
     * @param string $order
     */
    public function indexAction($char = '%')
    {
        $query = $this->objects->getQuery();
        // get selected objects only (title starting with specific letter)
        $query->matching($query->like($this->widgetConfiguration['property'], $char . '%'));
        $modifiedObjects = $query->execute();
        $this->view->assign('contentArguments', array(
            $this->widgetConfiguration['as'] => $modifiedObjects
        ));
        // create an array with all letters from A to Z
        foreach (range('A', 'Z') as $letter) {
            $letters[] = $letter;
        }
        $this->view->assign('letters', $letters);
        $this->view->assign('char', $char);
    }
}