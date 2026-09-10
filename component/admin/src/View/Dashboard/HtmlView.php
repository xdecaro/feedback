<?php
namespace xdecaro\Component\Feedback\Administrator\View\Dashboard;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

final class HtmlView extends BaseHtmlView
{
    public array $counts = [];
    public function display($tpl = null): void
    {
        $this->counts = (array) $this->getModel()->getCounts();
        ToolbarHelper::title(Text::_('COM_XDECAROFEEDBACK_DASHBOARD'), 'comments');
        if (Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_xdecarofeedback')) {
            ToolbarHelper::preferences('com_xdecarofeedback');
        }
        parent::display($tpl);
    }
}
