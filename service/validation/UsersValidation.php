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
    public function addValidation(string $name, string $email, string $password)
    {
        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'blank';
        } else {
            $errors['name'] = null;
        }

        if (empty($email)) {
            $errors['email'] = 'blank';
        } else {
            $errors['email'] = null;
        }

        if (strlen($password) < 4) {
            $errors['password'] = 'length';
        } else {
            $errors['password'] = null;
        }

        if (empty($password)) {
            $errors['password'] = 'blank';
        } else {
            $errors['password'] = null;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $errors['email'] = 'validate';
        } else {
            $errors['email'] = null;
        }

        return $errors;
    }
}
