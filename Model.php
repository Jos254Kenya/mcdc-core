<?php


namespace VMSMeruDairy\core;

/**
 * Class Model
 * Base class for handling data validation and interaction with the database.
 *
 * @package VMSMeruDairy\core
 */
class Model
{
// Validation rules
    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_MATCH = 'match';
    const RULE_UNIQUE = 'unique';
    const RULE_UPPERCASE = 'uppercase';
    const RULE_LOWERCASE = 'lowercase';
    const RULE_DIGIT = 'digit';
    const RULE_SPECIAL_CHAR = 'special_char';
    const RULE_PHONE = 'phone';
    const RULE_CHECKBOX = 'checkbox';


    public array $errors = [];

    /**
     * Loads data into model attributes.
     *
     * @param array $data The data to be loaded into the model.
     */
    public function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
    /**
     * Returns attribute names.
     *
     * @return array The attribute names.
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * @return array
     */

    /**
     * Returns attribute labels.
     *
     * @return array The attribute labels.
     */
    public function labels()
    {
        return [];
    }

    /**
     * Gets the label for an attribute.
     *
     * @param string $attribute The attribute name.
     * @return string The attribute label.
     */
    public function getLabel(string $attribute): string
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    /**
     * Returns validation rules.
     *
     * @return array The validation rules.
     */
    public function rules()
    {
        return [];
    }

    /**
     * Validates model attributes.
     *
     * @return bool Whether the validation succeeded.
     */
    public function validate():bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                }
                if($ruleName=== self::RULE_CHECKBOX && $value <1){
                    $this->addError($attribute, self::RULE_CHECKBOX);
                }
                if($ruleName===self::RULE_UPPERCASE && !preg_match('/[A-Z]/', $value)){
                    $this->addErrorByRule($attribute,self::RULE_UPPERCASE);
                }
                if($ruleName===self::RULE_LOWERCASE && !preg_match('/[a-z]/', $value)){
                    $this->addErrorByRule($attribute,self::RULE_LOWERCASE);
                }
                if($ruleName===self::RULE_SPECIAL_CHAR && !preg_match('/[^a-zA-Z\d]/', $value)){
                    $this->addErrorByRule($attribute,self::RULE_SPECIAL_CHAR);
                }
                if($ruleName===self::RULE_DIGIT && !preg_match('/\d/', $value)){
                    $this->addErrorByRule($attribute,self::RULE_DIGIT);
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorByRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorByRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $rule['min']]);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorByRule($attribute, self::RULE_MAX, ['max' => $rule['max']]);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addErrorByRule($attribute, self::RULE_MATCH, ['match' => $rule['match']]);
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $db = Application::$app->db;
                    $statement = $db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :$uniqueAttr");
                    $statement->bindValue(":$uniqueAttr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorByRule($attribute, self::RULE_UNIQUE);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * Returns error messages for validation rules.
     *
     * @return string[] The error messages.
     */
    public function errorMessages(): array
    {
        return [

            self::RULE_UNIQUE => 'Record with this {field}  already exists',
            self::RULE_REQUIRED => 'Please enter data in this field. This field is required',
            self::RULE_EMAIL => 'This field must contain a valid email address e.g example@karsch.com',
            self::RULE_MAX => 'The maximum length MUST not exceed {max} characters for this field',
            self::RULE_MIN => 'The minimum length is {min} characters for this field',
            self::RULE_MATCH =>'This field must be the same as {match} field',
            self::RULE_DIGIT => 'This field must contain at least one Number',
            self::RULE_LOWERCASE => 'This field MUST contain at least one Lowercase character',
            self::RULE_SPECIAL_CHAR => 'This field MUST contain at least one special character',
            self::RULE_UPPERCASE => 'This field MUST contain at least one Uppercase character',
        ];
    }

    /**
     * Returns an error message for a specific validation rule.
     *
     * @param string $rule The validation rule.
     * @return string The error message.
     */
    public function errorMessage(string $rule)
    {
        return $this->errorMessages()[$rule];
    }
    /**
     * Adds an error message for a specific attribute and rule.
     *
     * @param string $attribute The attribute name.
     * @param string $rule The validation rule.
     * @param array $params Additional parameters for error message formatting.
     */
    protected function addErrorByRule(string $attribute, string $rule, $params = [])
    {
        $params['field'] ??= $attribute;
        $errorMessage = $this->errorMessage($rule);
        foreach ($params as $key => $value) {
            $errorMessage = str_replace("{{$key}}", $value, $errorMessage);
        }
        $this->errors[$attribute][] = $errorMessage;
    }
    /**
     * Adds an error message for a specific attribute.
     *
     * @param string $attribute The attribute name.
     * @param string $message The error message.
     */
    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    /**
     * Checks if an attribute has an error.
     *
     * @param string $attribute The attribute name.
     * @return bool Whether the attribute has an error.
     */
    public function hasError(string $attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    /**
     * Returns the first error message for a specific attribute.
     *
     * @param string $attribute The attribute name.
     * @return string The first error message.
     */
    public function getFirstError(string $attribute)
    {
        $errors = $this->errors[$attribute] ?? [];
        return $errors[0] ?? '';
    }
}