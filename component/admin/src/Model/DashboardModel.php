<?php
namespace xdecaro\Component\Feedback\Administrator\Model;

defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
final class DashboardModel extends BaseDatabaseModel
{
    private const TABLES = [
        'questionnaires' => '#__xdecarofeedback_questionnaires',
        'templates' => '#__xdecarofeedback_templates',
        'questions' => '#__xdecarofeedback_questions',
        'submissions' => '#__xdecarofeedback_submissions',
    ];
    public function getCounts(): array
    {
        $counts = [];
        $db = $this->getDatabase();
        foreach (self::TABLES as $key => $table) {
            try {
                $query = $db->getQuery(true)->select('COUNT(*)')->from($db->quoteName($table));
                $counts[$key] = (int) $db->setQuery($query)->loadResult();
            } catch (\Throwable $e) {
                $counts[$key] = 0;
            }
        }
        return $counts;
    }
}
