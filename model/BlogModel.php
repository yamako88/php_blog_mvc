<?php

require_once('Model.php');

/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/15
 * Time: 16:47
 */
class BlogModel extends Model
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
     * @param $page
     * @return array
     */
    public function pages(int $session, int $page)
    {
        if (empty($page)) {
            $page = 1;
        }
        $page = max($page, 1);


        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS cnt FROM submission_form where user_id = ?');
        $stmt->bindValue(1, $session, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $cnt = $stmt->fetch();

            $maxPage = ceil($cnt['cnt'] / 5);

            $page = min($page, $maxPage);

            return array($page, $maxPage);

        } catch (PDOException $e) {

            exit('登録失敗' . $e->getMessage());
        }

    }


    /**
     * @param $session
     * @param $start
     * @return array
     */
    public function blog(int $session,int $start)
    {


        $statement = $this->pdo->prepare('SELECT * FROM (SELECT * FROM submission_form
                where user_id = ? order by id desc limit ?, 5) as formcategory left outer join category
                ON category.category_id = formcategory.category_id;');
        $statement->bindValue(1, $session, PDO::PARAM_INT);
        $statement->bindValue(2, $start, PDO::PARAM_INT);

        try {
            $statement->execute();
            $rows = $statement->fetchAll();
            $this->pdo = null;


            return $rows;
        } catch (PDOException $e) {

            exit('登録失敗' . $e->getMessage());
        }


    }


    /**
     * @param $session
     * @param $rowid
     * @return array
     */
    public function tag(int $session, int $rowid)
    {


        $statement = $this->pdo->prepare('SELECT * FROM (SELECT * FROM tag
        where user_id = ?) as formtag inner join (SELECT * FROM form_tag where form_id = ?)
        as formid ON formid.tag_id = formtag.id;');
        $statement->bindValue(1, $session, PDO::PARAM_INT);
        $statement->bindValue(2, $rowid, PDO::PARAM_INT);

        try {
            $statement->execute();
            $rowss = $statement->fetchAll();


            return $rowss;
        } catch (PDOException $e) {

            exit('登録失敗' . $e->getMessage());
        }


    }

    /**
     * @param $delete
     */
    public function delete(int $delete)
    {
        $del = $this->pdo->prepare('DELETE FROM submission_form WHERE id=?');
        $del->bindValue(1, $delete);
        $del->execute();
    }


}

