<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Validator;

use Concrete\Core\Validator\AbstractTranslatableValidator;
use InvalidArgumentException;
use ArrayAccess;

class HandleValidator extends AbstractTranslatableValidator
{

    public function isValid($mixed, ArrayAccess $error = null)
    {
        if ($mixed !== null && !is_string($mixed)) {
            throw new InvalidArgumentException(t('Invalid type supplied to validator.'));
        }

        if ($this->checkHandle($mixed) === false) {
            if ($error) {
                $error[] = t('The given handle is not valid.');
            }

            return false;
        }

        return true;
    }

    private function checkHandle($handle)
    {
        $prevChar = null;

        for ($i = 0; $i < strlen($handle); $i++) {
            $curChar = ord(substr($handle, $i, 1));

            // allowed: a-z, _ when last char was not _
            if (!($curChar >= 97 && $curChar <= 122 || ($curChar === 95 && $prevChar !== $curChar))) {
                return false;
            }

            $prevChar = $curChar;
        }

        // cut of last char when _ is at end
        if (ord(substr($handle, strlen($handle) - 1)) === 95) {
            return false;
        }

        /*
         * Check if the handle is or contains a reserved word
         */

        if (strtolower($handle) === "id") {
            return false; // id is reserved for internal usage
        } else if (strtolower($handle) === "displayvalue") {
            return false; // id is reserved for internal usage
        }

        return true;
    }
}
