<?php
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
HTMLHelper::_('behavior.multiselect');
$user = Factory::getApplication()->getIdentity();
$canEdit = $user->authorise('core.edit', 'com_xdecarofeedback');
$types = ['stars','scale','satisfaction','yesno','single_choice','multiple_choice','short_text','long_text','nps'];
?>
<form action="<?= Route::_('index.php?option=com_xdecarofeedback&view=questions') ?>" method="post" name="adminForm" id="adminForm">
<div class="xdecaro-scope xdecaro-feedback">
  <div class="xdecaro-feedback__filters row g-2 mb-3">
    <div class="col-lg-4"><label class="visually-hidden" for="filter_search"><?= Text::_('JSEARCH_FILTER') ?></label><input id="filter_search" type="search" name="filter_search" class="form-control" value="<?= $this->escape((string) $this->state->get('filter.search')) ?>" placeholder="<?= Text::_('COM_XDECAROFEEDBACK_SEARCH_QUESTIONS') ?>"></div>
    <div class="col-lg-3"><label class="visually-hidden" for="filter_type"><?= Text::_('COM_XDECAROFEEDBACK_FIELD_QUESTION_TYPE') ?></label><select id="filter_type" name="filter_type" class="form-select" onchange="this.form.submit()"><option value=""><?= Text::_('COM_XDECAROFEEDBACK_ALL_TYPES') ?></option><?php foreach ($types as $type) : ?><option value="<?= $type ?>" <?= (string) $this->state->get('filter.type') === $type ? 'selected' : '' ?>><?= Text::_('COM_XDECAROFEEDBACK_TYPE_' . strtoupper($type)) ?></option><?php endforeach; ?></select></div>
    <div class="col-lg-2"><label class="visually-hidden" for="filter_category"><?= Text::_('COM_XDECAROFEEDBACK_FIELD_CATEGORY') ?></label><select id="filter_category" name="filter_category" class="form-select" onchange="this.form.submit()"><option value=""><?= Text::_('COM_XDECAROFEEDBACK_ALL_CATEGORIES') ?></option><?php foreach ($this->categories as $category) : ?><option value="<?= $this->escape($category) ?>" <?= (string) $this->state->get('filter.category') === $category ? 'selected' : '' ?>><?= $this->escape($category) ?></option><?php endforeach; ?></select></div>
    <div class="col-lg-2"><label class="visually-hidden" for="filter_state"><?= Text::_('JSTATUS') ?></label><select id="filter_state" name="filter_state" class="form-select" onchange="this.form.submit()"><option value=""><?= Text::_('JOPTION_SELECT_PUBLISHED') ?></option><option value="1" <?= (string) $this->state->get('filter.state') === '1' ? 'selected' : '' ?>><?= Text::_('JPUBLISHED') ?></option><option value="0" <?= (string) $this->state->get('filter.state') === '0' ? 'selected' : '' ?>><?= Text::_('JUNPUBLISHED') ?></option></select></div>
    <div class="col-lg-1 d-grid"><button class="btn btn-primary" type="submit"><?= Text::_('JSEARCH_FILTER_SUBMIT') ?></button></div>
  </div>
  <?php if (!$this->items) : ?>
    <div class="alert alert-info"><?= Text::_('COM_XDECAROFEEDBACK_NO_QUESTIONS') ?></div>
  <?php else : ?>
  <div class="table-responsive xdecaro-feedback__table-wrap"><table class="table table-striped align-middle xdecaro-feedback__records">
    <thead><tr><th class="w-1"><input type="checkbox" name="checkall-toggle" onclick="Joomla.checkAll(this)" aria-label="<?= Text::_('JGLOBAL_CHECK_ALL') ?>"></th><th><?= Text::_('COM_XDECAROFEEDBACK_FIELD_TITLE') ?></th><th><?= Text::_('COM_XDECAROFEEDBACK_FIELD_QUESTION_TYPE') ?></th><th><?= Text::_('COM_XDECAROFEEDBACK_FIELD_CATEGORY') ?></th><th><?= Text::_('JSTATUS') ?></th></tr></thead>
    <tbody><?php foreach ($this->items as $i => $item) : ?><tr>
      <td data-label=""><?= HTMLHelper::_('grid.id', $i, (int) $item->id) ?></td>
      <td data-label="<?= Text::_('COM_XDECAROFEEDBACK_FIELD_TITLE') ?>"><?php if ($canEdit) : ?><a href="<?= Route::_('index.php?option=com_xdecarofeedback&task=question.edit&id=' . (int) $item->id) ?>"><strong><?= $this->escape($item->title) ?></strong></a><?php else : ?><strong><?= $this->escape($item->title) ?></strong><?php endif; ?><div class="text-muted small mt-1"><?= $this->escape($item->prompt) ?></div></td>
      <td data-label="<?= Text::_('COM_XDECAROFEEDBACK_FIELD_QUESTION_TYPE') ?>"><span class="badge bg-info text-dark"><?= Text::_('COM_XDECAROFEEDBACK_TYPE_' . strtoupper((string) $item->question_type)) ?></span></td>
      <td data-label="<?= Text::_('COM_XDECAROFEEDBACK_FIELD_CATEGORY') ?>"><?= $this->escape((string) $item->category) ?></td>
      <td data-label="<?= Text::_('JSTATUS') ?>"><span class="badge <?= (int) $item->state === 1 ? 'bg-success' : 'bg-secondary' ?>"><?= (int) $item->state === 1 ? Text::_('JPUBLISHED') : Text::_('JUNPUBLISHED') ?></span></td>
    </tr><?php endforeach; ?></tbody>
  </table></div>
  <?= $this->pagination->getListFooter() ?>
  <?php endif; ?>
</div>
<input type="hidden" name="task" value=""><input type="hidden" name="boxchecked" value="0"><input type="hidden" name="filter_order" value="<?= $this->escape((string) $this->state->get('list.ordering')) ?>"><input type="hidden" name="filter_order_Dir" value="<?= $this->escape((string) $this->state->get('list.direction')) ?>"><?= HTMLHelper::_('form.token') ?>
</form>
