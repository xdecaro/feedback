<?php
namespace xdecaro\Component\Feedback\Administrator\Model;
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\DatabaseQuery;
use Joomla\Database\ParameterType;
final class QuestionsModel extends ListModel
{
    public function __construct($config = [])
    {
        $config['filter_fields'] ??= ['id','title','prompt','question_type','category','state','created','ordering'];
        parent::__construct($config);
    }
    protected function populateState($ordering = 'a.ordering', $direction = 'asc'): void
    {
        $app = $this->getApplication();
        $this->setState('filter.search', $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.state', $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
        $this->setState('filter.type', $app->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '', 'cmd'));
        $this->setState('filter.category', $app->getUserStateFromRequest($this->context . '.filter.category', 'filter_category', '', 'string'));
        parent::populateState($ordering, $direction);
    }
    protected function getListQuery(): DatabaseQuery
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select(['a.id','a.title','a.prompt','a.question_type','a.category','a.state','a.ordering','a.language','a.created'])
            ->from($db->quoteName('#__xdecarofeedback_questions', 'a'));
        $state = $this->getState('filter.state');
        if ($state !== '') {
            $state = (int) $state;
            $query->where($db->quoteName('a.state') . ' = :state')->bind(':state', $state, ParameterType::INTEGER);
        } else {
            $query->where($db->quoteName('a.state') . ' >= 0');
        }
        $type = trim((string) $this->getState('filter.type'));
        if ($type !== '') {
            $query->where($db->quoteName('a.question_type') . ' = :type')->bind(':type', $type);
        }
        $category = trim((string) $this->getState('filter.category'));
        if ($category !== '') {
            $query->where($db->quoteName('a.category') . ' = :category')->bind(':category', $category);
        }
        $search = trim((string) $this->getState('filter.search'));
        if ($search !== '') {
            $like = '%' . str_replace(' ', '%', $search) . '%';
            $query->where('(' . $db->quoteName('a.title') . ' LIKE :s1 OR ' . $db->quoteName('a.prompt') . ' LIKE :s2 OR ' . $db->quoteName('a.category') . ' LIKE :s3)')
                ->bind(':s1', $like)->bind(':s2', $like)->bind(':s3', $like);
        }
        $order = (string) $this->state->get('list.ordering', 'a.ordering');
        $allowed = ['a.id','a.title','a.prompt','a.question_type','a.category','a.state','a.created','a.ordering'];
        if (!in_array($order, $allowed, true)) {
            $order = 'a.ordering';
        }
        $direction = strtoupper((string) $this->state->get('list.direction', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';
        return $query->order($order . ' ' . $direction)->order($db->quoteName('a.id') . ' ASC');
    }
    public function getCategories(): array
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('DISTINCT ' . $db->quoteName('category'))
            ->from($db->quoteName('#__xdecarofeedback_questions'))
            ->where($db->quoteName('category') . " <> ''")
            ->where($db->quoteName('state') . ' >= 0')
            ->order($db->quoteName('category') . ' ASC');
        return array_values(array_filter(array_map('strval', (array) $db->setQuery($query)->loadColumn())));
    }
}
