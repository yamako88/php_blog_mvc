<?php

require_once('Model.php');

/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/18
 * Time: 15:26
 */
class CategoryModel extends Model
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
        $statement = $this->pdo->prepare("SELECT * FROM category where user_id_category = ?");
        $statement->bindValue(1, $session);

        //executeでクエリを実行
        $statement->execute();
        $rows = $statement->fetchAll();
        $this->pdo = null;

        return $rows;
    }

    /**
     * @param $session
     * @param $category
     * @return array
     */
    public function validation($session, $category)
    {
        $error = [];

        //    エラー項目の確認
        if ($category == '') {
            $error['category_name'] = 'blank';
        }


//    重複アカウントのチェック
        if (empty($error)) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) AS cnt FROM category WHERE category_name=? and user_id_category=?');
            $stmt->bindValue(1, $category);
            $stmt->bindValue(2, $session);
            $stmt->execute();

            $record = $stmt->fetch();
            if ($record['cnt'] > 0) {
                $error['category_name'] = 'duplicate';
            }


        }

        return $error;
    }

    /**
     * @param $session
     * @param $category
     */
    public function add($session, $category)
    {
        $stmt = $this->pdo->prepare('insert into category (category_name, user_id_category) values(?, ?)');
        $stmt->bindParam(1, $category, PDO::PARAM_STR);
        $stmt->bindParam(2, $session, PDO::PARAM_STR);
        $stmt->execute();

    }

    /**
     * @param $delete
     */
    public function delete($delete)
    {
        $del = $this->pdo->prepare('DELETE FROM category WHERE category_id=?');
        $del->bindValue(1, $delete);
        $del->execute();
    }

    /**
     * @param $session
     * @param $category
     * @return array
     */
    public function validation_edit($session, $category)
    {
        $error = [];

        //    エラー項目の確認
        if ($category == '') {
            $error['category_name'] = 'blank';
        } else {
//            $error['category_name'] = '';
        }


//    重複アカウントのチェック
        if (empty($error)) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) AS cnt FROM category WHERE category_name=? and user_id_category=?');
            $stmt->bindValue(1, $category);
            $stmt->bindValue(2, $session);
            $stmt->execute();

            $record = $stmt->fetch();
            if ($record['cnt'] > 0) {
                $error['category_name'] = 'duplicate';
            } else {
//                $error['category_name'] = '';
            }


        }

        return $error;
    }

    /**
     * @param $category_id
     * @param $category
     */
    public function apdate($category_id, $category)
    {
        $stmt = $this->pdo->prepare('update category set category_name = ? where category_id = ?');
        $stmt->bindParam(1, $category, PDO::PARAM_STR);
        $stmt->bindParam(2, $category_id, PDO::PARAM_STR);
        $stmt->execute();

    }

}