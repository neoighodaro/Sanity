<?php namespace CreativityKills\Sanity;

class Sanitizer  {

    /**
     * Sanitizes an array of data against a set of rules.
     *
     * @param  array $data
     * @param  array $rules
     * @return array
     */
    public function sanitize(array $data, array $rules = null)
    {
        $rules = $rules ?: $this->getRules();

        foreach ($rules as $field => $rules)
        {
            if ( ! isset($data[$field])) continue;

            $data[$field] = $this->applySanitizersTo($data[$field], $rules);
        }

        return $data;
    }

    /**
     * Apply sanitizers to a field value.
     *
     * @param  string       $value
     * @param  array|string $sanitizers
     * @return string
     */
    protected function applySanitizersTo($value, $sanitizers)
    {
        foreach ($this->splitSanitizers($sanitizers) as $sanitizer)
        {
            $customSanitizer = $this->getCustomSanitizerMethod($sanitizer);

            if (method_exists($this, $customSanitizer))
            {
                $value = call_user_func([$this, $customSanitizer], $value);
            }
            else
            {
                $value = call_user_func($sanitizer, $value);
            }
        }

        return $value;
    }

    /**
     * Split sanitizers into array from string.
     *
     * @param  array|string $sanitizers
     * @return array
     */
    protected function splitSanitizers($sanitizers)
    {
        return is_array($sanitizers) ? $sanitizers : explode('|', $sanitizers);
    }

    /**
     * Get rules defined in a custom class.
     *
     * @return mixed
     */
    protected function getRules()
    {
        return $this->rules;
    }

    /**
     * Converts snake case sanitizer to Camel Cased sanitizer.
     *
     * @param  string $sanitizer
     * @return string
     */
    protected function getCustomSanitizerMethod($sanitizer)
    {
        // Convert the sanitizer to an array with uppercase first letters
        // e.g phone_number would be ['Phone', 'Number']
        $customSanitizer = array_filter(explode('_', $sanitizer), 'ucfirst');

        return 'sanitize' . implode('', $customSanitizer);
    }

}
