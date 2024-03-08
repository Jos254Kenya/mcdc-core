<?php

namespace VMSMeruDairy\core\form;

use VMSMeruDairy\core\Model;

/**
 * Class Form
 * Helper class for generating HTML forms.
 * Use this class to create and manage HTML forms.
 *
 * @package VMSMeruDairy\core\form
 */
class Form
{
    /**
     * Begins the HTML form.
     *
     * @param string $action The URL to which the form data will be submitted.
     * @param string $method The HTTP method for submitting the form (e.g., 'GET' or 'POST').
     * @param array $options Additional attributes for the form element (optional).
     * @return Form An instance of the Form class.
     */
    public static function begin(string $action, string $method, array $options = []): Form
    {
        $attributes = [];
        foreach ($options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }
        echo sprintf('<form id="submitForm" action="%s" method="%s" %s>', $action, $method, implode(" ", $attributes));
        return new Form();
    }

    /**
     * Ends the HTML form.
     */
    public static function end(): void
    {
        echo '</form>';
    }

    /**
     * Generates a form field.
     *
     * @param Model $model The model associated with the form field.
     * @param string $attribute The attribute name of the form field.
     * @return Field An instance of the Field class representing the form field.
     */
    public function field(Model $model, string $attribute): Field
    {
        return new Field($model, $attribute);
    }

    /**
     * Generates a select form field.
     *
     * @param Model $model The model associated with the select form field.
     * @param string $attribute The attribute name of the select form field.
     * @param array $options The options for the select field.
     * @param string $valueKey The key in the options array representing the value.
     * @param string $textname The key in the options array representing the text.
     * @return Select An instance of the Select class representing the select form field.
     */
    public function select(Model $model, string $attribute, array $options, string $valueKey, string $textname): Select
    {
        return new Select($model, $attribute, $options, $valueKey, $textname);
    }
}