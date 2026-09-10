<?php
defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
$wa = $this->getDocument()->getWebAssetManager();
if (class_exists(\xdecaro\Core\Asset\AssetService::class)) {
    (new \xdecaro\Core\Asset\AssetService())->useComponents($wa);
}
$wa->useStyle('com_xdecarofeedback.admin');
$info = $this->information;
$e = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<div class="xdecaro-scope xdecaro-feedback">
  <header class="xdecaro-feedback__hero"><div><span class="xdecaro-feedback__eyebrow"><?= Text::_('COM_XDECAROFEEDBACK_SUITE') ?></span><h1><?= Text::_('COM_XDECAROFEEDBACK_INFORMATION') ?></h1><p><?= Text::_('COM_XDECAROFEEDBACK_INFORMATION_DESC') ?></p></div></header>
  <section class="xdecaro-feedback__grid xdecaro-feedback__grid--two">
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_PRODUCT') ?></h2><dl><div><dt><?= Text::_('COM_XDECAROFEEDBACK_VERSION') ?></dt><dd><?= $e($info['version'] ?? '') ?></dd></div><div><dt><?= Text::_('COM_XDECAROFEEDBACK_COMPONENT') ?></dt><dd>com_xdecarofeedback</dd></div><div><dt><?= Text::_('COM_XDECAROFEEDBACK_PACKAGE') ?></dt><dd>pkg_xdecarofeedback</dd></div></dl></article>
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_ENVIRONMENT') ?></h2><dl><div><dt>Joomla</dt><dd><?= $e($info['joomla'] ?? '') ?></dd></div><div><dt>PHP</dt><dd><?= $e($info['php'] ?? '') ?></dd></div><div><dt>Core</dt><dd><?= $e(($info['coreVersion'] ?? '') ?: Text::_('COM_XDECAROFEEDBACK_NOT_DETECTED')) ?></dd></div></dl></article>
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_EXTENSIONS_INCLUDED') ?></h2><p><?= Text::_('COM_XDECAROFEEDBACK_EXTENSIONS_INCLUDED_DESC') ?></p></article>
    <article class="xdecaro-feedback__card"><h2><?= Text::_('COM_XDECAROFEEDBACK_UPDATES') ?></h2><p><?= Text::_('COM_XDECAROFEEDBACK_UPDATES_DEV_DESC') ?></p></article>
  </section>
  <section class="xdecaro-feedback__card xdecaro-feedback__full"><h2><?= Text::_('COM_XDECAROFEEDBACK_CONNECTED_COMPONENTS') ?></h2><div class="xdecaro-feedback__badges"><?php foreach (($info['connected'] ?? []) as $name => $installed) : ?><span class="badge <?= $installed ? 'bg-success' : 'bg-secondary' ?>"><?= $e($name) ?> · <?= $installed ? Text::_('COM_XDECAROFEEDBACK_DETECTED') : Text::_('COM_XDECAROFEEDBACK_OPTIONAL') ?></span><?php endforeach; ?></div><p><?= Text::_('COM_XDECAROFEEDBACK_CONNECTED_COMPONENTS_DESC') ?></p></section>
  <section class="xdecaro-feedback__card xdecaro-feedback__full"><h2><?= Text::_('COM_XDECAROFEEDBACK_DIAGNOSTICS') ?></h2><dl><div><dt><?= Text::_('COM_XDECAROFEEDBACK_CORE_REQUIREMENT') ?></dt><dd><span class="badge <?= !empty($info['coreReady']) ? 'bg-success' : 'bg-danger' ?>"><?= !empty($info['coreReady']) ? Text::_('JYES') : Text::_('JNO') ?></span></dd></div><div><dt><?= Text::_('COM_XDECAROFEEDBACK_DATABASE') ?></dt><dd><?= (int) ($info['tablesReady'] ?? 0) ?> / <?= (int) ($info['tablesExpected'] ?? 0) ?> <?= Text::_('COM_XDECAROFEEDBACK_TABLES_READY') ?></dd></div></dl></section>
</div>
