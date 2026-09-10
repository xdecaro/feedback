<?php
namespace xdecaro\Component\Feedback\Administrator\Model;
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
final class QuestionModel extends AdminModel
{
    protected $text_prefix = 'COM_XDECAROFEEDBACK';
    private const TYPES = ['stars','scale','satisfaction','yesno','single_choice','multiple_choice','short_text','long_text','nps'];
    private const CHOICE_TYPES = ['single_choice','multiple_choice'];
    public function getTable($type = 'Question', $prefix = 'Administrator', $config = []): Table
    {
        return parent::getTable($type, $prefix, $config);
    }
    public function getForm($data = [], $loadData = true)
    {
        return $this->loadForm('com_xdecarofeedback.question', 'question', ['control' => 'jform', 'load_data' => $loadData]);
    }
    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_xdecarofeedback.edit.question.data', []);
        if ($data) {
            return $data;
        }
        $item = $this->getItem();
        if (!$item) {
            return $item;
        }
        $options = json_decode((string) ($item->options_json ?? ''), true);
        $settings = json_decode((string) ($item->settings_json ?? ''), true);
        $item->options_text = is_array($options) ? implode("\n", array_map('strval', $options)) : '';
        $item->min_value = (int) ($settings['min'] ?? 1);
        $item->max_value = (int) ($settings['max'] ?? 5);
        $item->min_label = (string) ($settings['min_label'] ?? '');
        $item->max_label = (string) ($settings['max_label'] ?? '');
        $item->required_default = !empty($settings['required_default']) ? 1 : 0;
        return $item;
    }
    public function save($data): bool
    {
        $data['title'] = trim(strip_tags((string) ($data['title'] ?? '')));
        $data['prompt'] = trim(strip_tags((string) ($data['prompt'] ?? '')));
        $data['question_type'] = trim((string) ($data['question_type'] ?? ''));
        $data['category'] = trim(strip_tags((string) ($data['category'] ?? '')));
        if ($data['title'] === '') {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_TITLE_REQUIRED'));
            return false;
        }
        if ($data['prompt'] === '') {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_PROMPT_REQUIRED'));
            return false;
        }
        if (mb_strlen($data['title']) > 255) {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_TITLE_TOO_LONG'));
            return false;
        }
        if (!in_array($data['question_type'], self::TYPES, true)) {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_TYPE_INVALID'));
            return false;
        }
        if (mb_strlen($data['category']) > 100) {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_CATEGORY_TOO_LONG'));
            return false;
        }
        $options = [];
        if (in_array($data['question_type'], self::CHOICE_TYPES, true)) {
            $lines = preg_split('/\R/u', (string) ($data['options_text'] ?? '')) ?: [];
            foreach ($lines as $line) {
                $line = trim(strip_tags((string) $line));
                if ($line === '') {
                    continue;
                }
                if (mb_strlen($line) > 255) {
                    $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_OPTION_TOO_LONG'));
                    return false;
                }
                if (!in_array($line, $options, true)) {
                    $options[] = $line;
                }
            }
            if (count($options) < 2) {
                $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_OPTIONS_MIN'));
                return false;
            }
            if (count($options) > 50) {
                $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_OPTIONS_MAX'));
                return false;
            }
        }
        $settings = ['required_default' => !empty($data['required_default'])];
        switch ($data['question_type']) {
            case 'stars':
                $max = (int) ($data['max_value'] ?? 5);
                if ($max < 3 || $max > 10) {
                    $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_STARS_RANGE'));
                    return false;
                }
                $settings['min'] = 1;
                $settings['max'] = $max;
                break;
            case 'scale':
                $min = (int) ($data['min_value'] ?? 1);
                $max = (int) ($data['max_value'] ?? 5);
                if ($min < -100 || $max > 100 || $min >= $max) {
                    $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_SCALE_RANGE'));
                    return false;
                }
                $settings['min'] = $min;
                $settings['max'] = $max;
                $settings['min_label'] = mb_substr(trim(strip_tags((string) ($data['min_label'] ?? ''))), 0, 120);
                $settings['max_label'] = mb_substr(trim(strip_tags((string) ($data['max_label'] ?? ''))), 0, 120);
                break;
            case 'satisfaction':
                $settings['min'] = 1;
                $settings['max'] = 5;
                $settings['min_label'] = mb_substr(trim(strip_tags((string) ($data['min_label'] ?? ''))), 0, 120);
                $settings['max_label'] = mb_substr(trim(strip_tags((string) ($data['max_label'] ?? ''))), 0, 120);
                break;
            case 'nps':
                $settings['min'] = 0;
                $settings['max'] = 10;
                break;
        }
        $data['options_json'] = $options ? json_encode($options, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
        $data['settings_json'] = json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        unset($data['options_text'], $data['min_value'], $data['max_value'], $data['min_label'], $data['max_label'], $data['required_default']);
        return parent::save($data);
    }
    protected function prepareTable($table): void
    {
        $now = Factory::getDate()->toSql();
        $userId = (int) Factory::getApplication()->getIdentity()->id;
        if (empty($table->id)) {
            $table->created = $now;
            $table->created_by = $userId;
        } else {
            $table->modified = $now;
            $table->modified_by = $userId;
        }
    }
    protected function canDelete($record): bool
    {
        return Factory::getApplication()->getIdentity()->authorise('core.delete', 'com_xdecarofeedback');
    }
    protected function canEditState($record): bool
    {
        return Factory::getApplication()->getIdentity()->authorise('core.edit.state', 'com_xdecarofeedback');
    }
}
