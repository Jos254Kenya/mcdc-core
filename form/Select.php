<?php

namespace VMSMeruDairy\core\form;

use VMSMeruDairy\core\Model;

/**
 * Class Select
 * Represents a select form field.
 *
 * @package VMSMeruDairy\core\form
 */
class Select extends BaseSelect
{
    public const CLASS_FORM_CONTROL = 'form-select';

    /**
     * @var string The CSS class for the select form field.
     */
    public string $class;

    /**
     * @var array The options for the select form field.
     */
    public array $options;

    /**
     * @var string The key in the options array representing the value.
     */
    public string $valueKey;

    /**
     * @var string The key in the options array representing the text.
     */
    public string $textKey;

    /**
     * Select constructor.
     *
     * @param Model $model The model associated with the select form field.
     * @param string $attribute The attribute name of the select form field.
     * @param array $options The options for the select form field.
     * @param string $valueKey The key in the options array representing the value (default is 'id').
     * @param string $textKey The key in the options array representing the text (default is 'name').
     */
    public function __construct(Model $model, string $attribute, array $options = [], string $valueKey = 'id', string $textKey = 'name')
    {
        parent::__construct($model, $attribute);
        $this->options = $options;
        $this->class = self::CLASS_FORM_CONTROL;
        $this->valueKey = $valueKey;
        $this->textKey = $textKey;
    }

    /**
     * Renders the select form field.
     *
     * @return string The HTML representation of the select form field.
     */
    public function renderSelect(): string
    {
        $selectHtml = sprintf('<select class="%s%s" name="%s">',
            $this->class,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute
        );

        foreach ($this->options as $option) {
            $value = $option[$this->valueKey];
            $text = $option[$this->textKey];
            $selected = $value == $this->model->{$this->attribute} ? 'selected' : '';
            $selectHtml .= sprintf('<option value="%s" %s>%s</option>', $value, $selected, $text);
        }

        $selectHtml .= '</select>';
        return $selectHtml;
    }
}