<?php

namespace Core;

class Validation
{
    protected $errors = [];

    /**
     * Validate data against rules
     */
    public static function validate(array $data, array $rules)
    {
        $validator = new self();
        
        foreach ($rules as $field => $ruleString) {
            $ruleArray = explode('|', $ruleString);
            
            foreach ($ruleArray as $rule) {
                $validator->validateField($field, $data[$field] ?? null, $rule, $data);
            }
        }
        
        return [
            'valid' => empty($validator->errors),
            'errors' => $validator->errors
        ];
    }

    /**
     * Validate individual field
     */
    protected function validateField($field, $value, $rule, $data)
    {
        // Parse rule and parameters
        $params = [];
        if (strpos($rule, ':') !== false) {
            list($rule, $paramString) = explode(':', $rule, 2);
            $params = explode(',', $paramString);
        }

        $method = 'validate' . ucfirst($rule);
        
        if (method_exists($this, $method)) {
            $this->$method($field, $value, $params, $data);
        }
    }

    /**
     * Validate required field
     */
    protected function validateRequired($field, $value, $params, $data)
    {
        if (empty($value) && $value !== '0') {
            $this->addError($field, ucfirst($field) . ' is required');
        }
    }

    /**
     * Validate email
     */
    protected function validateEmail($field, $value, $params, $data)
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, ucfirst($field) . ' must be a valid email address');
        }
    }

    /**
     * Validate minimum length
     */
    protected function validateMin($field, $value, $params, $data)
    {
        $min = $params[0];
        
        if (!empty($value) && strlen($value) < $min) {
            $this->addError($field, ucfirst($field) . " must be at least {$min} characters");
        }
    }

    /**
     * Validate maximum length
     */
    protected function validateMax($field, $value, $params, $data)
    {
        $max = $params[0];
        
        if (!empty($value) && strlen($value) > $max) {
            $this->addError($field, ucfirst($field) . " must not exceed {$max} characters");
        }
    }

    /**
     * Validate field matches another field
     */
    protected function validateMatches($field, $value, $params, $data)
    {
        $matchField = $params[0];
        
        if ($value !== ($data[$matchField] ?? null)) {
            $this->addError($field, ucfirst($field) . ' does not match ' . ucfirst($matchField));
        }
    }

    /**
     * Validate unique value in database
     */
    protected function validateUnique($field, $value, $params, $data)
    {
        if (empty($value)) {
            return;
        }
        
        $table = $params[0];
        $column = $params[1] ?? $field;
        $exceptId = $params[2] ?? null;
        
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
        
        if ($exceptId) {
            $sql .= " AND id != :except_id";
        }
        
        $stmt = $db->prepare($sql);
        
        // Only bind the except_id parameter if it exists
        $bindParams = ['value' => $value];
        if ($exceptId) {
            $bindParams['except_id'] = $exceptId;
        }
        
        $stmt->execute($bindParams);
        
        $result = $stmt->fetch();
        
        if ($result['count'] > 0) {
            $this->addError($field, ucfirst($field) . ' is already taken');
        }
    }

    /**
     * Validate numeric value
     */
    protected function validateNumeric($field, $value, $params, $data)
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, ucfirst($field) . ' must be a number');
        }
    }

    /**
     * Validate alpha characters only
     */
    protected function validateAlpha($field, $value, $params, $data)
    {
        if (!empty($value) && !ctype_alpha($value)) {
            $this->addError($field, ucfirst($field) . ' must contain only letters');
        }
    }

    /**
     * Validate alphanumeric characters
     */
    protected function validateAlphanumeric($field, $value, $params, $data)
    {
        if (!empty($value) && !ctype_alnum($value)) {
            $this->addError($field, ucfirst($field) . ' must contain only letters and numbers');
        }
    }

    /**
     * Validate URL
     */
    protected function validateUrl($field, $value, $params, $data)
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, ucfirst($field) . ' must be a valid URL');
        }
    }

    /**
     * Validate file upload
     */
    protected function validateFile($field, $value, $params, $data)
    {
        if (!Request::hasFile($field)) {
            $this->addError($field, ucfirst($field) . ' is required');
        }
    }

    /**
     * Validate image file
     */
    protected function validateImage($field, $value, $params, $data)
    {
        if (Request::hasFile($field)) {
            $file = Request::file($field);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            
            if (!in_array($file['type'], $allowedTypes)) {
                $this->addError($field, ucfirst($field) . ' must be a valid image (jpg, png, gif, webp)');
            }
        }
    }

    /**
     * Add error message
     */
    protected function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        
        $this->errors[$field][] = $message;
    }
}

