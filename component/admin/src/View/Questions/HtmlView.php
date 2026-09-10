<?php
namespace xdecaro\Component\Feedback\Administrator\View\Questions;
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
final class HtmlView extends BaseHtmlView
{
    public array $items = [];
    public array $categories = [];
    public $pagination;
    public $state;
    public function display($tpl = null): void
    {
        $user = Factory::getApplication()->getIdentity();
        if (!$user->authorise('core.manage', 'com_xdecarofeedback')) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }
        $this->items = (array) $this->get('Items');
        $this->categories = (array) $this->get('Categories');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $wa = $this->getDocument()->getWebAssetManager();
        if (class_exists(\xdecaro\Core\Asset\AssetService::class)) {
            (new \xdecaro\Core\Asset\AssetService())->useComponents($wa);
        }
        $wa->useStyle('com_xdecarofeedback.admin');
        ToolbarHelper::title(Text::_('COM_XDECAROFEEDBACK_QUESTION_LIBRARY'), 'list');
        if ($user->authorise('core.create', 'com_xdecarofeedback')) {
            ToolbarHelper::addNew('question.add');
        }
        if ($user->authorise('core.edit', 'com_xdecarofeedback')) {
            ToolbarHelper::editList('question.edit');
        }
        if ($user->authorise('core.edit.state', 'com_xdecarofeedback')) {
            ToolbarHelper::publish('questions.publish', 'JTOOLBAR_PUBLISH', true);
            ToolbarHelper::unpublish('questions.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            ToolbarHelper::trash('questions.trash');
        }
        if ($user->authorise('core.delete', 'com_xdecarofeedback')) {
            ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'questions.delete');
        }
        parent::display($tpl);
    }
}
