<?php

namespace VMSMeruDairy\core\form;

use VMSMeruDairy\core\Model;

/**
 * Class BaseSelect
 * Base class for select form fields.
 * Extend this class to define custom select form field types.
 *
 * @package VMSMeruDairy\core\form
 */
abstract class BaseSelect
{
    /**
     * @var Model The model associated with the select form field.
     */
    public Model $model;

    /**
     * @var string The attribute name of the select form field.
     */
    public string $attribute;

    /**
     * BaseSelect constructor.
     *
     * @param Model $model The model associated with the select form field.
     * @param string $attribute The attribute name of the select form field.
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * Converts the select form field object to its string representation.
     *
     * @return string The HTML representation of the select form field.
     */
    public function __toString(): string
    {
        return sprintf('
                    %s
                    <label>%s</label>
                    <div class="invalid-feedback">
                        %s
                    </div>',
            $this->renderSelect(),
            $this->model->getLabel($this->attribute),
            $this->model->getFirstError($this->attribute)
        );
    }

    /**
     * Renders the select element of the select form field.
     *
     * @return string The HTML representation of the select element.
     */
    abstract public function renderSelect(): string;
}
