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
    public function addValidation($name, $email, $password){
        $errors = [];
//        if (empty($name)){
//            $errors['username'] = 'カテゴリ名がありません。<br>';
//        }
//        if (mb_strlen($name) > 80){
//            $errors['username'] = 'カテゴリ名が長すぎます。<br>';
//        }

        if ($name == '') {
            $error['name'] = 'blank';
        }
        if ($email == '') {
            $error['email'] = 'blank';
        }
        if (strlen($password) < 4) {
            $error['password'] = 'length';
        }
        if ($password == '') {
            $error['password'] = 'blank';
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            $error['email'] = 'validate';
        }


        return $errors;
    }
}