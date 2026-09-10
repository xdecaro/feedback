<?php
defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
$wa = $this->getDocument()->getWebAssetManager();
if (class_exists(\xdecaro\Core\Asset\AssetService::class)) {
    (new \xdecaro\Core\Asset\AssetService())->useComponents($wa);
}
$wa->useStyle('com_xdecarofeedback.admin');
$counts = $this->counts;
?>
<div class="xdecaro-scope xdecaro-feedback">
  <header class="xdecaro-feedback__hero">
    <div><span class="xdecaro-feedback__eyebrow"><?= Text::_('COM_XDECAROFEEDBACK_SUITE') ?></span><h1><?= Text::_('COM_XDECAROFEEDBACK_DASHBOARD') ?></h1><p><?= Text::_('COM_XDECAROFEEDBACK_DASHBOARD_DESC') ?></p></div>
    <span class="badge bg-warning text-dark"><?= Text::_('COM_XDECAROFEEDBACK_DEVELOPMENT') ?></span>
  </header>
  <section class="xdecaro-feedback__metrics" aria-label="<?= Text::_('COM_XDECAROFEEDBACK_METRICS') ?>">
    <article><strong><?= (int) ($counts['questionnaires'] ?? 0) ?></strong><span><?= Text::_('COM_XDECAROFEEDBACK_QUESTIONNAIRES') ?></span></article>
    <article><strong><?= (int) ($counts['templates'] ?? 0) ?></strong><span><?= Text::_('COM_XDECAROFEEDBACK_TEMPLATES') ?></span></article>
    <article><strong><?= (int) ($counts['questions'] ?? 0) ?></strong><span><?= Text::_('COM_XDECAROFEEDBACK_QUESTION_LIBRARY') ?></span></article>
    <article><strong><?= (int) ($counts['submissions'] ?? 0) ?></strong><span><?= Text::_('COM_XDECAROFEEDBACK_RESPONSES') ?></span></article>
  </section>
  <section class="xdecaro-feedback__grid">
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_AUTHORING') ?></h2><p><?= Text::_('COM_XDECAROFEEDBACK_AUTHORING_DESC') ?></p></article>
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_INTEGRATIONS') ?></h2><p><?= Text::_('COM_XDECAROFEEDBACK_INTEGRATIONS_DESC') ?></p></article>
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_REPORTING') ?></h2><p><?= Text::_('COM_XDECAROFEEDBACK_REPORTING_DESC') ?></p></article>
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_PRIVACY') ?></h2><p><?= Text::_('COM_XDECAROFEEDBACK_PRIVACY_DESC') ?></p></article>
  </section>
  <p class="xdecaro-feedback__footer"><a class="btn btn-outline-primary" href="<?= Route::_('index.php?option=com_xdecarofeedback&view=information') ?>"><?= Text::_('COM_XDECAROFEEDBACK_OPEN_INFORMATION') ?></a></p>
</div>
