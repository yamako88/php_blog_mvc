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

            $stmt = $this->pdo->prepare('insert into users (name, email, password) values(?, ?, ?)');
            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $email, PDO::PARAM_STR);
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(3, $password, PDO::PARAM_STR);

            try {
                $stmt->execute();

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
    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email=?');

        $stmt->bindParam(1, $email, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $user = $stmt->fetch();


            // ハッシュ化されたパスワードがマッチするかどうかを確認
            if (password_verify($password, $user[3])) {

                return $user;
            } else {
                return [];
            }

        } catch (PDOException $e) {

            exit('登録失敗' . $e->getMessage());
        }


    }
}
