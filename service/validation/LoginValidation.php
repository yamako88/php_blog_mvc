<?php
/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/15
 * Time: 12:47
 */

class LoginValidation
{
    /**
     * @param $email
     * @param $password
     * @return array
     */
    public function addValidation(string $email, string $password)
    {
        $errors = [];

        if (empty($email)) {
            $errors['login'] = 'blank';
        }
        if (empty($password)) {
            $errors['login'] = 'blank';
        }


        return $errors;
    }

}
