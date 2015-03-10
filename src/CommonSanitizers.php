<?php namespace CreativityKills\Sanity;

trait CommonSanitizers {

    /**
     * Replaces multiple white spaces to a single white space.
     *
     * @param  $value
     * @return string
     */
    public function sanitizeWhitespaces($value)
    {
        return preg_replace('/\s+/', ' ', $value);
    }

}