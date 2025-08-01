<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Logical XOR.
 *
 * @since Class available since Release 3.0.0
 */
class PHPUnit_Framework_Constraint_Xor extends PHPUnit_Framework_Constraint
{
    /**
     * @var PHPUnit_Framework_Constraint[]
     */
    protected $constraints = array();

    /**
     * @param PHPUnit_Framework_Constraint[] $constraints
     */
    public function setConstraints(array $constraints)
    {
        $this->constraints = array();

        foreach ($constraints as $constraint) {
            if (!($constraint instanceof PHPUnit_Framework_Constraint)) {
                $constraint = new PHPUnit_Framework_Constraint_IsEqual(
                    $constraint
                );
            }

            $this->constraints[] = $constraint;
        }
    }

    /**
     * Evaluates the constraint for parameter $other
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param mixed  $other        Value or object to evaluate.
     * @param string $description  Additional information about the test
     * @param bool   $returnResult Whether to return a result or throw an exception
     *
     * @return mixed
     *
     * @throws PHPUnit_Framework_ExpectationFailedException
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        $success    = true;
        $lastResult = null;
        $constraint = null;

        foreach ($this->constraints as $constraint) {
            $result = $constraint->evaluate($other, $description, true);

            if ($result === $lastResult) {
                $success = false;
                break;
            }

            $lastResult = $result;
        }

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $this->fail($other, $description);
        }
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        $text = '';

        foreach ($this->constraints as $key => $constraint) {
            if ($key > 0) {
                $text .= ' xor ';
            }

            $text .= $constraint->toString();
        }

        return $text;
    }

    /**
     * Counts the number of constraint elements.
     *
     * @return int
     *
     * @since  Method available since Release 3.4.0
     */
    public function count()
    {
        $count = 0;

        foreach ($this->constraints as $constraint) {
            $count += oldCount($constraint);
        }

        return $count;
    }
}
