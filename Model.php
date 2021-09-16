<?php

namespace App\core;

abstract class Model
{
    public const RULE_EMAIL = 'email';
    public const RULE_REQURED = 'requied';
    public const RULE_MAX = 'max';
    public const RULE_MIN = 'min';
    public const RULE_MATCH = 'match';

    public array $errors = [];

    abstract public function rules(): array;

    abstract public function tableName(): string;

    abstract public function attrs(): array;

    public function loadData($data)
    {
        foreach ($data as $key => $attr) {
            if (property_exists($this, $key)) {
                $this->{$key} = $attr;
            }
        }
    }

    public function validation()
    {
        foreach ($this->rules() as $key => $rules) {
            $value = $this->{$key};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQURED && !$value) {
                    $this->addError(self::RULE_REQURED, $key);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < 8) {
                    $this->addError(self::RULE_MIN, $key, $rule);
                }
                if ($ruleName === self::RULE_MATCH && strlen($value) !== strlen($this->{$rule['match']})) {
                    $this->addError(self::RULE_MATCH, $key, $rule);
                }
                if ($ruleName === self::RULE_EMAIL && strlen($value) !== strlen($this->{$rule['RULE_EMAIL']})) {
                    $this->addError(self::RULE_EMAIL, $key, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    public function addError($rule, $ruleName, $rules = [] ?? null)
    {
        $message = $this->errorMessage()[$rule] ?? null;
        foreach ($rules as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$ruleName][] = $message;
    }

    public function getlabel($attr)
    {
        return $this->label()[$attr];
    }

    public function label(): array
    {
        return [
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'pass' => 'Password',
            'cpass' => 'Confurm Password',
        ];
    }

    public function errorMessage()
    {
        return [
            self::RULE_REQURED => 'please this field is required',
            self::RULE_EMAIL => 'email must be valide email',
            self::RULE_MAX => 'password must be at least {max}.',
            self::RULE_MIN => 'password must be at least {min}.',
            self::RULE_MATCH => 'it must be the same as {match}',
        ];
    }

    public function firstError($attr)
    {
        return $this->errors[$attr][0] ?? '';
    }
}
