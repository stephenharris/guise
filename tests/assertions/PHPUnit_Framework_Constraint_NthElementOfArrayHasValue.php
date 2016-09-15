<?php

namespace stephenharris\PHPUnit;

/**
 * Constraint that asserts that the array it is evaulated for as a value at the nth position matching the expected value
 *
 * The ordinal to check for is passed in the constructor.
 */
class PHPUnit_Framework_Constraint_NthElementOfArrayHasValue extends \PHPUnit_Framework_Constraint
{
    /**
     * @var int
     */
    protected $ordinal;
    /**
     * @var mixed
     */
    protected $expected;

    /**
     * @param int|string $key
     */
    public function __construct($ordinal, $value)
    {
        parent::__construct();
        $this->ordinal = (int)$ordinal;
        $this->expected = $value;

        if ($this->ordinal < 1) {
            throw new \InvalidArgumentException('Ordinal must be an integer greater than 1');
        }
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     *
     * @return bool
     */
    protected function matches($other)
    {
        if (count($other) < $this->ordinal) {
            return false;
        }
        $value = array_shift(array_slice($other, ($this->ordinal - 1), 1, true));

        return $value == $this->expected;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return 'has the value ' . $this->exporter->export($this->expected) . ' at the ' . $this->exporter->export($this->ordinal) . ' position';
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other Evaluated value or object.
     *
     * @return string
     */
    protected function failureDescription($other)
    {
        return 'an array ' . $this->toString();
    }
}