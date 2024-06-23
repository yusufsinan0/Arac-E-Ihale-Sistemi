<?php

namespace Gleox\Validation;

class Validator
{
    protected $rules = [];
    protected $rulesErrorMessages = [];
    protected $errors = [];

    public function __construct()
    {
        $this->defineRules([
            'email' => function($value) {
                return filter_var($value, FILTER_VALIDATE_EMAIL);
            },
            'required' => function($value) {
                return !empty($value);
            },
            'trim' => function($value) {
                return trim($value);
            },
            'min' => function($value, $min) {
                return strlen($value) >= $min;
            },
            'max' => function($value, $max) {
                return strlen($value) <= $max;
            },
            'between' => function($value, $min, $max) {
                return strlen($value) >= $min && strlen($value) <= $max;
            },
            'numeric' => function($value) {
                return is_numeric($value);
            },
            'integer' => function($value) {
                return is_int($value);
            },
            'float' => function($value) {
                return is_float($value);
            },
            'array' => function($value) {
                return is_array($value);
            },
            'same' => function($value, $other) {
                return $value === $other;
            },
            'different' => function($value, $other) {
                return $value !== $other;
            },
        ]);
    }

    public function defineRule($name, callable $callback, $errorMessage = null)
    {
        $this->rules[$name] = $callback;
        if ($errorMessage) {
            $this->rulesErrorMessages[$name] = $errorMessage;
        } else {
            $this->rulesErrorMessages[$name] = "Validation failed for rule: $name";
        }
    }

    public function defineRules($rules)
    {
        foreach ($rules as $name => $callback) {
            $this->defineRule($name, $callback);
        }
    }

    //errors
    public function defineErrorMessage($name, $errorMessage)
    {
        $this->rulesErrorMessages[$name] = $errorMessage;
    }

    public function defineErrorMessages($errorMessages)
    {
        foreach ($errorMessages as $name => $errorMessage) {
            $this->defineErrorMessage($name, $errorMessage);
        }
    }

    public function validate(&$data, $rules)
    {
        foreach ($rules as $field => $ruleSet) {
            $ruleNames = explode('|', $ruleSet);
            foreach ($ruleNames as $ruleName) {
                $ruleParts = explode(':', $ruleName);
                $rule = $ruleParts[0];
                $params = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];

                if (isset($this->rules[$rule])) {
                    if (!isset($data[$field])) {
                        $data[$field] = null;
                    }
                    $result = call_user_func($this->rules[$rule], $data[$field], ...$params);
                    if (!$result) {
                        $this->addError($field, $this->rulesErrorMessages[$rule]);
                    } else if ($result !== true) {
                        $data[$field] = $result;
                    }
                }
            }
        }

        return empty($this->errors);
    }

    protected function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}