<?php
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;
final class pkg_xdecarofeedbackInstallerScript
{
    private const MINIMUM_CORE = '1.5.9';
    public function preflight($type, $parent): bool
    {
        if ($type === 'uninstall') {
            return true;
        }
        $version = $this->getInstalledCoreVersion();
        if ($version !== '' && version_compare($version, self::MINIMUM_CORE, '>=')) {
            return true;
        }
        Factory::getApplication()->enqueueMessage('Feedback by xdecaro requires Core by xdecaro ' . self::MINIMUM_CORE . ' or later.', 'error');
        return false;
    }
    private function getInstalledCoreVersion(): string
    {
        if (class_exists(\xdecaro\Core\Version::class)) {
            return trim((string) \xdecaro\Core\Version::VERSION);
        }
        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->getQuery(true)
                ->select($db->quoteName('manifest_cache'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('package'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('pkg_xdecarocore'));
            $manifest = json_decode((string) $db->setQuery($query, 0, 1)->loadResult(), true);
            return is_array($manifest) ? trim((string) ($manifest['version'] ?? '')) : '';
        } catch (\Throwable $e) {
            return '';
        }
    }
}
