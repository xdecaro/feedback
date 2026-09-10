<?php
namespace xdecaro\Component\Feedback\Administrator\Table;
defined('_JEXEC') or die;
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
        $this->title = trim((string) $this->title);
        $this->prompt = trim((string) $this->prompt);
        $this->question_type = trim((string) $this->question_type);
        $this->category = trim((string) $this->category);
        if ($this->title === '') {
            $this->setError('Question title is required.');
            return false;
        }
        if ($this->prompt === '') {
            $this->setError('Question prompt is required.');
            return false;
        }
        if (!in_array($this->question_type, self::TYPES, true)) {
            $this->setError('Unsupported question type.');
            return false;
        }
        foreach (['options_json','settings_json'] as $field) {
            $value = trim((string) ($this->{$field} ?? ''));
            if ($value !== '' && json_decode($value, true) === null && json_last_error() !== JSON_ERROR_NONE) {
                $this->setError('Invalid question configuration.');
                return false;
            }
            $this->{$field} = $value !== '' ? $value : null;
        }
        return parent::check();
    }
}
