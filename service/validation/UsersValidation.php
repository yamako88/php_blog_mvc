<?php


/**
 * Class PostValidation
 */
class UsersValidation
{
    /**
     * @param $name
     * @return array
     */
    public function addValidation($name, $email, $password)
    {
        $errors = [];

        if ($name == '') {
            $errors['name'] = 'blank';
        }
        if ($email == '') {
            $errors['email'] = 'blank';
        }
        if (strlen($password) < 4) {
            $errors['password'] = 'length';
        }
        if ($password == '') {
            $errors['password'] = 'blank';
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $errors['email'] = 'validate';
        }

        return $errors;
    }
}
