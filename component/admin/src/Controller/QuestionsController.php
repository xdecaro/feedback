<?php
namespace xdecaro\Component\Feedback\Administrator\Controller;
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\AdminController;
final class QuestionsController extends AdminController
{
    public function getModel($name = 'Question', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }
}
