<?php

namespace common\traits\objects;

use yii\base\InvalidConfigException;

/**
 * Trait InitValidationTrait
 */
trait InitValidationTrait
{
    /**
     * Validates $fields on empty, if field is empty, throws InvalidConfigException.
     *
     * @param mixed ...$fields
     *
     * @throws InvalidConfigException
     */
    protected function validateOnRequire(...$fields): void
    {
        foreach ($fields as $field) {
            $value = $this->$field;

            if (empty($value)) {
                throw new InvalidConfigException('Param "' . $field . '" is required');
            }
        }
    }
}
