<?php

namespace VMSMeruDairy\core\form;

/**
 * Class TextareaField
 * Represents a textarea form field.
 *
 * @package VMSMeruDairy\core\form
 */
class TextareaField extends BaseField
{

    /**
     * Renders the textarea input field.
     *
     * @return string The HTML representation of the textarea input field.
     */
    public function renderInput(): string
    {
        return sprintf('<textarea class="form-control%s" name="%s">%s</textarea>',
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->model->{$this->attribute},
        );
    }
}
