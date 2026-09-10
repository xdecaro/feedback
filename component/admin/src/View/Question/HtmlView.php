<?php
namespace xdecaro\Component\Feedback\Administrator\View\Question;
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
final class HtmlView extends BaseHtmlView
{
    public $form;
    public $item;
    public function display($tpl = null): void
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $user = Factory::getApplication()->getIdentity();
        $isNew = empty($this->item->id);
        if (!$user->authorise($isNew ? 'core.create' : 'core.edit', 'com_xdecarofeedback')) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }
        $wa = $this->getDocument()->getWebAssetManager();
        if (class_exists(\xdecaro\Core\Asset\AssetService::class)) {
            (new \xdecaro\Core\Asset\AssetService())->useComponents($wa);
        }
        $wa->useStyle('com_xdecarofeedback.admin');
        ToolbarHelper::title($isNew ? Text::_('COM_XDECAROFEEDBACK_QUESTION_NEW') : Text::_('COM_XDECAROFEEDBACK_QUESTION_EDIT'), 'list');
        ToolbarHelper::apply('question.apply');
        ToolbarHelper::save('question.save');
        if ($user->authorise('core.create', 'com_xdecarofeedback')) {
            ToolbarHelper::save2new('question.save2new');
        }
        ToolbarHelper::cancel('question.cancel');
        parent::display($tpl);
    }
}
