<?php namespace spec\CreativityKills\Sanity;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use CreativityKills\Sanity\Sanitizer;

class SanitizerSpec extends ObjectBehavior
{
    function it_should_use_rules_array_as_callback()
    {
        $input = ['name' => 'John Doe'];

        $rules = ['name' => 'strtolower'];

        $expected = ['name' => 'john doe'];

        $this->sanitize($input, $rules)->shouldReturn($expected);
    }

    function it_should_parse_multiple_pipe_separated_rules_as_callback()
    {
        $input = ['name' => '  JOHN DOE'];

        $rules = ['name' => 'trim|strtolower|ucwords'];

        $expected = ['name' => 'John Doe'];

        $this->sanitize($input, $rules)->shouldReturn($expected);
    }

    function it_should_parse_multiple_array_of_rules_as_callback()
    {
        $input = ['name' => 'John Doe  '];

        $rules = ['name' => ['trim', 'strtoupper']];

        $expected = ['name' => 'JOHN DOE'];

        $this->sanitize($input, $rules)->shouldReturn($expected);
    }

    function it_fetches_sanitize_rules_off_of_a_class_extension()
    {
        $this->beAnInstanceOf('spec\CreativityKills\Sanity\DummySanitizer');

        $input = ['name' => 'John Doe  '];

        $expected = ['name' => 'john doe'];

        $this->sanitize($input)->shouldReturn($expected);
    }

    function it_invokes_custom_rules_defined_in_a_class_extension()
    {
        $this->beAnInstanceOf('spec\CreativityKills\Sanity\DummySanitizer');

        $input = ['phone' => '  555-555-5555'];

        $expected = ['phone' => '5555555555'];

        $this->sanitize($input)->shouldReturn($expected);
    }

    function it_converts_snake_cased_rules_to_camel_cased_rules_on_custom_rules()
    {
        $this->beAnInstanceOf('spec\CreativityKills\Sanity\DummySanitizer');

        $input = ['cc' => '5555 5555 5555 5555'];

        $expected = ['cc' => '5555-5555-5555-5555'];

        $this->sanitize($input)->shouldReturn($expected);
    }
}

class DummySanitizer extends Sanitizer {

    protected $rules = [
        'name'  => 'trim|strtolower',
        'phone' => 'trim|phone',
        'cc'    => 'space_to_dash'
    ];

    public function sanitizePhone($value)
    {
        return str_replace('-', '', $value);
    }

    public function sanitizeSpaceToDash($value)
    {
        return str_replace(' ', '-', $value);
    }

}