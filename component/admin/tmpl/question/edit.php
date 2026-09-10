<?php
defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
HTMLHelper::_('behavior.formvalidator');
?>
<form action="<?= Route::_('index.php?option=com_xdecarofeedback&layout=edit&id=' . (int) ($this->item->id ?? 0)) ?>" method="post" name="adminForm" id="question-form" class="form-validate">
<div class="xdecaro-scope xdecaro-feedback xdecaro-feedback__editor">
  <div class="alert alert-info mb-3"><?= Text::_('COM_XDECAROFEEDBACK_QUESTION_EDITOR_HELP') ?></div>
  <?= HTMLHelper::_('uitab.startTabSet', 'questionTabs', ['active' => 'question']) ?>
  <?= HTMLHelper::_('uitab.addTab', 'questionTabs', 'question', Text::_('COM_XDECAROFEEDBACK_FIELDSET_QUESTION')) ?><?= $this->form->renderFieldset('question') ?><?= HTMLHelper::_('uitab.endTab') ?>
  <?= HTMLHelper::_('uitab.addTab', 'questionTabs', 'scale', Text::_('COM_XDECAROFEEDBACK_FIELDSET_BEHAVIOUR')) ?><?= $this->form->renderFieldset('scale') ?><?= HTMLHelper::_('uitab.endTab') ?>
  <?= HTMLHelper::_('uitab.addTab', 'questionTabs', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')) ?><?= $this->form->renderFieldset('publishing') ?><?= HTMLHelper::_('uitab.endTab') ?>
  <?= HTMLHelper::_('uitab.endTabSet') ?>
</div>
<input type="hidden" name="task" value=""><?= HTMLHelper::_('form.token') ?>
</form>
