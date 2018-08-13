<?php

require_once('Model.php');

/**
 * Class PostModel
 */
class UserModel extends Model
{

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * PostModel constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->pdo = parent::getPdo();
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     */
    public function add($name, $email, $password)
    {
        if (empty($error)) {
        $_SESSION['join'] = $_POST;

        $stmt = $this->pdo->prepare('insert into users (name,email,password) values(?,?,?)');
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(3, $password, PDO::PARAM_STR);


            try {
                $stmt->execute();
                unset($_SESSION['join']);

                header('Location: ../public/views/thanks.php');
                exit();

            } catch (PDOException $e) {

                exit('登録失敗' . $e->getMessage());
            }


    }
    }




    /**
     * @param $email
     * @param $password
     * @return array
     */
//    public function login($email, $password)
//    {
//        $stmt = $this->pdo->prepare('SELECT email, password
//                FROM users
//                WHERE  email= :email');
//
//        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
//
//        try {
//            $stmt->execute();
//            $user = $stmt->fetchAll();
//
//        } catch (PDOException $e) {
//
//            exit('登録失敗' . $e->getMessage());
//        }
//
//        // ハッシュ化されたパスワードがマッチするかどうかを確認
//        if (password_verify($password, $user[0]['password'])) {
//            return $user;
//        }else{
//            return [];
//        }
//    }
}