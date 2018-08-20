<?php

require_once('Model.php');

/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/20
 * Time: 10:13
 */
class TagModel extends Model
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
     * @return array
     */
    public function show($session)
    {
        $statement = $this->pdo->prepare("SELECT * FROM tag where user_id=?");
        $statement->bindValue(1, $session);

        //executeでクエリを実行
        $statement->execute();
        $rows = $statement->fetchAll();
        $this->pdo = null;

        return $rows;
    }

    /**
     * @param $session
     * @param $tag
     * @return array
     */
    public function validation($session, $tag)
    {
        $error = [];

        //    エラー項目の確認
        if ($tag == '') {
            $error['tag_name'] = 'blank';
        }


//    重複アカウントのチェック
        if (empty($error)) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) AS cnt FROM tag WHERE tag_name=? and user_id=?');
            $stmt->bindValue(1, $tag);
            $stmt->bindValue(2, $session);
            $stmt->execute();

            $record = $stmt->fetch();
            if ($record['cnt'] > 0) {
                $error['tag_name'] = 'duplicate';
            }


        }

        return $error;
    }

    /**
     * @param $session
     * @param $tag
     */
    public function add($session, $tag)
    {
        $stmt = $this->pdo->prepare('insert into tag (tag_name, user_id) values(?, ?)');
        $stmt->bindParam(1, $tag, PDO::PARAM_STR);
        $stmt->bindParam(2, $session, PDO::PARAM_STR);
        $stmt->execute();

    }

    /**
     * @param $delete
     */
    public function delete($delete)
    {
        $del = $this->pdo->prepare('DELETE FROM tag WHERE id=?');
        $del->bindValue(1, $delete);
        $del->execute();
    }

    /**
     * @param $session
     * @param $tag
     * @return array
     */
    public function validation_edit($session, $tag)
    {
        $error = [];

        //    エラー項目の確認
        if ($tag == '') {
            $error['tag_name'] = 'blank';
        } else {
//            $error['category_name'] = '';
        }


//    重複アカウントのチェック
        if (empty($error)) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) AS cnt FROM tag WHERE tag_name=? and user_id=?');
            $stmt->bindValue(1, $tag);
            $stmt->bindValue(2, $session);
            $stmt->execute();

            $record = $stmt->fetch();
            if ($record['cnt'] > 0) {
                $error['tag_name'] = 'duplicate';
            } else {
//                $error['category_name'] = '';
            }


        }

        return $error;
    }

    /**
     * @param $tag_id
     * @param $tag
     */
    public function apdate($tag_id, $tag)
    {
        $stmt = $this->pdo->prepare('update tag set tag_name=? where id=?');
        $stmt->bindParam(1, $tag, PDO::PARAM_STR);
        $stmt->bindParam(2, $tag_id, PDO::PARAM_STR);
        $stmt->execute();

    }

}