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
    public function addValidation($email, $password)
    {
        $errors = [];

        if ($email == '') {
            $errors['login'] = 'blank';
        }
        if ($password == '') {
            $errors['login'] = 'blank';
        }


        return $errors;
    }

}
