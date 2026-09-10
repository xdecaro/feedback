<?php
namespace xdecaro\Component\Feedback\Administrator\Table;
defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
final class QuestionTable extends Table
{
    private const TYPES = ['stars','scale','satisfaction','yesno','single_choice','multiple_choice','short_text','long_text','nps'];
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__xdecarofeedback_questions', 'id', $db);
    }
    public function check(): bool
    {
        $this->title = trim(strip_tags((string) $this->title));
        $this->prompt = trim(strip_tags((string) $this->prompt));
        $this->question_type = trim((string) $this->question_type);
        $this->category = trim(strip_tags((string) $this->category));
        if ($this->title === '') {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_TITLE_REQUIRED'));
            return false;
        }
        if ($this->prompt === '') {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_PROMPT_REQUIRED'));
            return false;
        }
        if (!in_array($this->question_type, self::TYPES, true)) {
            $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_TYPE_INVALID'));
            return false;
        }
        foreach (['options_json','settings_json'] as $field) {
            $value = trim((string) ($this->{$field} ?? ''));
            if ($value !== '') {
                json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->setError(Text::_('COM_XDECAROFEEDBACK_ERROR_CONFIGURATION_INVALID'));
                    return false;
                }
            }
            $this->{$field} = $value !== '' ? $value : null;
        }
        return parent::check();
    }
}
