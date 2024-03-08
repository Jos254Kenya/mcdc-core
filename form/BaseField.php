<?php

namespace VMSMeruDairy\core\form;

use VMSMeruDairy\core\Model;

/**
 * Class BaseField
 * Base class for form fields.
 * Extend this class to define custom form field types.
 *
 * @package VMSMeruDairy\core\form
 */
abstract class BaseField
{
    /**
     * @var Model The model associated with the form field.
     */
    public Model $model;

    /**
     * @var string The attribute name of the form field.
     */
    public string $attribute;

    /**
     * @var string The type of the form field.
     */
    public string $type;

    /**
     * BaseField constructor.
     *
     * @param Model $model The model associated with the form field.
     * @param string $attribute The attribute name of the form field.
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * Converts the form field object to its string representation.
     *
     * @return string The HTML representation of the form field.
     */
    public function __toString(): string
    {
        return sprintf('
                    %s
                    <label>%s</label>
                    <div class="invalid-feedback">
                        %s
                    </div>',
            $this->renderInput(),
            $this->model->getLabel($this->attribute),
            $this->model->getFirstError($this->attribute)
        );
    }

    /**
     * Renders the input element of the form field.
     *
     * @return string The HTML representation of the input element.
     */
    abstract public function renderInput(): string;
}
