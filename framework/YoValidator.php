<?php

class YoValidator {
    protected $rules = [];
    protected $errors = [];
    protected $data = [];
    protected $currentField = null;

    protected $messages = [
        'required'      => '{label}不能为空',
        'email'         => '{label}格式不正确',
        'numeric'       => '{label}必须是数字',
        'minLen'        => '{label}长度不能小于{param}',
        'maxLen'        => '{label}长度不能超过{param}',
        'url'           => '{label}网址格式不正确',
        'ip'            => '{label}IP地址格式不正确',
        'alpha'         => '{label}仅限字母',
        'alphaNum'      => '{label}仅限字母和数字',
        'alphaDash'     => '{label}仅限数字,字母,下划线',
        'alphaNumSpace' => '{label}仅限字母,数字和空格',
    ];

    public function setMessages(array $msgs) {
        $this->messages = array_merge($this->messages, $msgs);
        return $this;
    }

    public function rule($field, $label) {
        $this->currentField = $field;
        $this->rules[$field] = [
            'label' => $label,
            'rules' => [],
            'custom_msg' => []
        ];
        $val = isset($_POST[$field]) ? $_POST[$field] : null;

        $this->data[$field] = is_scalar($val) ? trim($val) : $val;

        return $this;
    }

    /**
     * 自定义当前字段的错误消息数组
     */
    public function message(array $msgs) {
        if ($this->currentField) {
            $this->rules[$this->currentField]['custom_msg'] = $msgs;
        }
        return $this;
    }

    public function required() {
        $this->rules[$this->currentField]['rules']['required'] = true;
        return $this;
    }

    public function email() {
        $this->rules[$this->currentField]['rules']['email'] = true;
        return $this;
    }

    public function numeric() {
        $this->rules[$this->currentField]['rules']['numeric'] = true;
        return $this;
    }

    public function minLength($len) {
        $this->rules[$this->currentField]['rules']['minLen'] = $len;
        return $this;
    }

    public function maxLength($len) {
        $this->rules[$this->currentField]['rules']['maxLen'] = $len;
        return $this;
    }

    public function alpha() {
        $this->rules[$this->currentField]['rules']['alpha'] = '/^[a-zA-Z]+$/';
        return $this;
    }

    public function alphaNumeric() {
        $this->rules[$this->currentField]['rules']['alphaNum'] = '/^[a-zA-Z0-9]+$/';
        return $this;
    }

    public function alphaDash() {
        $this->rules[$this->currentField]['rules']['alphaDash'] = '/^[a-zA-Z0-9_]+$/';
        return $this;
    }

    public function alphaNumericSpaces() {
        $this->rules[$this->currentField]['rules']['alphaNumSpace'] = '/^[a-zA-Z0-9 ]+$/';
        return $this;
    }

    public function url() {
        $this->rules[$this->currentField]['rules']['url'] = true;
        return $this;
    }

    public function ip() {
        $this->rules[$this->currentField]['rules']['ip'] = true;
        return $this;
    }

    public function run() {
        foreach ($this->rules as $field => $item) {
            $value = isset($this->data[$field]) ? $this->data[$field] : '';
            $label = $item['label'];

            foreach ($item['rules'] as $rule => $param) {
                $isInvalid = false;
                if ($rule === 'required') {
                    if ($value === null || $value === '') $isInvalid = true;
                } elseif ($value !== null && $value !== '') {
                    $isInvalid = !$this->validateRule($rule, $value, $param);
                }

                if ($isInvalid) {
                    $tpl = isset($item['custom_msg'][$rule]) ? $item['custom_msg'][$rule] :
                        (isset($this->messages[$rule]) ? $this->messages[$rule] : 'Invalid Input');

                    $this->errors[$field] = str_replace(['{label}', '{param}'], [$label, $param], $tpl);
                    return false;
                }
            }
        }
        return true;
    }

    protected function validateRule($rule, $value, $param) {
        switch ($rule) {
            case 'email':
                return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
            case 'numeric':
                return is_numeric($value);
            case 'minLen':
                return mb_strlen((string)$value) >= $param;
            case 'maxLen':
                return mb_strlen((string)$value) <= $param;
            case 'url':
                return (bool)filter_var($value, FILTER_VALIDATE_URL);
            case 'ip':
                return (bool)filter_var($value, FILTER_VALIDATE_IP);
            case 'alpha':
            case 'alphaNum':
            case 'alphaDash':
            case 'alphaNumSpace':
                return (bool)preg_match($param, $value);
            default: return true;
        }
    }

    public function getData() {
        return $this->data;
    }
    public function getErrorInfo() {
        return reset($this->errors) ?: '';
    }

}