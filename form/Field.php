<?php

namespace VMSMeruDairy\core\form;

use VMSMeruDairy\core\Model;

/**
 * Class Field
 * Represents a form field for input elements.
 * Extend this class to define custom input field types.
 *
 * @package VMSMeruDairy\core\form
 */
class Field extends BaseField
{
    /**
     * Input field types constants.
     */
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_TIME = 'time';
    public const TYPE_DATE = 'date';
    public const TYPE_FILE = 'file';
    public const TYPE_CHECKBOX = 'checkbox';

    /**
     * CSS class constants.
     */
    public const CLASS_FORM_CONTROL = 'form-control';
    public const CLASS_FORM = '';

    /**
     * @var string The label of the form field.
     */
    public string $label;

    /**
     * @var string The CSS class of the form field.
     */
    public string $class;

    /**
     * Field constructor.
     *
     * @param Model $model The model associated with the form field.
     * @param string $attribute The attribute name of the form field.
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->class = self::CLASS_FORM_CONTROL;
        parent::__construct($model, $attribute);
    }

    /**
     * Renders the input element of the form field.
     *
     * @return string The HTML representation of the input element.
     */
    public function renderInput(): string
    {
        return sprintf('<input type="%s" class="%s%s" name="%s" value="%s">',
            $this->type,
            $this->class,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->model->{$this->attribute},
        );
    }

    /**
     * Sets the field type to password.
     *
     * @return $this The Field instance.
     */
    public function passwordField(): self
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    /**
     * Sets the field type to date.
     *
     * @return $this The Field instance.
     */
    public function dateField(): self
    {
        $this->type = self::TYPE_DATE;
        return $this;
    }

    /**
     * Sets the field type to time.
     *
     * @return $this The Field instance.
     */
    public function timeField(): self
    {
        $this->type = self::TYPE_TIME;
        return $this;
    }

    /**
     * Removes the CSS class from the form field.
     *
     * @return $this The Field instance.
     */
    public function noClass(): self
    {
        $this->class = self::CLASS_FORM;
        return $this;
    }

    /**
     * Sets the field type to file.
     *
     * @return $this The Field instance.
     */
    public function fileField(): self
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }

    /**
     * Sets the field type to number.
     *
     * @return $this The Field instance.
     */
    public function numberField(): self
    {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }
}