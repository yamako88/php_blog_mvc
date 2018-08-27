<?php

require_once('Model.php');

/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/15
 * Time: 14:30
 */
class SessionModel extends Model
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
     * @param $session
     * @param $time
     * @return mixed
     */
    public function session(int $session, int $time)
    {
        if (isset($session) && $time + 3600 > time()) {

//    ログインしている
            $time = time();
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id=?');
            $stmt->bindParam(1, $session, PDO::PARAM_STR);


            try {
                $stmt->execute();
                $user = $stmt->fetch();

                return $user;
            } catch (PDOException $e) {

                exit('登録失敗' . $e->getMessage());
            }


        }
    }


}
