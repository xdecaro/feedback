<?php
namespace xdecaro\Component\Feedback\Administrator\View\Information;

defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
final class HtmlView extends BaseHtmlView
{
    public array $information = [];
    public function display($tpl = null): void
    {
        $this->information = (array) $this->getModel()->getInformation();
        ToolbarHelper::title(Text::_('COM_XDECAROFEEDBACK_INFORMATION'), 'info-circle');
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_xdecarofeedback')) {
            ToolbarHelper::preferences('com_xdecarofeedback');
        }
        parent::display($tpl);
    }
}
