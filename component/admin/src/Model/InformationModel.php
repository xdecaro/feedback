<?php
namespace xdecaro\Component\Feedback\Administrator\Model;

defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use xdecaro\Component\Feedback\Administrator\Version;
final class InformationModel extends BaseDatabaseModel
{
    private const EXPECTED_TABLES = [
        '#__xdecarofeedback_templates',
        '#__xdecarofeedback_template_questions',
        '#__xdecarofeedback_questions',
        '#__xdecarofeedback_questionnaires',
        '#__xdecarofeedback_questionnaire_questions',
        '#__xdecarofeedback_submissions',
        '#__xdecarofeedback_answers',
    ];
    public function getInformation(): array
    {
        $coreVersion = class_exists(\xdecaro\Core\Version::class) ? (string) \xdecaro\Core\Version::VERSION : '';
        $tableList = [];
        try {
            $tableList = $this->getDatabase()->getTableList();
        } catch (\Throwable $e) {
            $tableList = [];
        }
        $ready = 0;
        foreach (self::EXPECTED_TABLES as $table) {
            if (in_array($this->getDatabase()->replacePrefix($table), $tableList, true)) {
                ++$ready;
            }
        }
        return [
            'version' => Version::VERSION,
            'joomla' => defined('JVERSION') ? JVERSION : '',
            'php' => PHP_VERSION,
            'coreVersion' => $coreVersion,
            'coreReady' => $coreVersion !== '' && version_compare($coreVersion, '1.5.9', '>='),
            'tablesReady' => $ready,
            'tablesExpected' => count(self::EXPECTED_TABLES),
            'connected' => $this->getConnectedProducts(),
        ];
    }
    private function getConnectedProducts(): array
    {
        $products = [
            'Courses' => 'com_xdecarocourses',
            'Events' => 'com_xdecaroevents',
            'Competitions' => 'com_xdecarocompetitions',
            'Membership' => 'com_xdecaromembership',
            'Bookings' => 'com_xdecarobookings',
        ];
        $result = [];
        $db = $this->getDatabase();
        foreach ($products as $name => $element) {
            try {
                $query = $db->getQuery(true)
                    ->select($db->quoteName('extension_id'))
                    ->from($db->quoteName('#__extensions'))
                    ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
                    ->where($db->quoteName('element') . ' = ' . $db->quote($element));
                $result[$name] = (bool) $db->setQuery($query, 0, 1)->loadResult();
            } catch (\Throwable $e) {
                $result[$name] = false;
            }
        }
        return $result;
    }
}
