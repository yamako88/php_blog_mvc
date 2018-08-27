<?php

require_once('Model.php');

/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/17
 * Time: 13:30
 */
class PostModel extends Model
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
     * @param $title
     * @param $text
     * @param $category_id
     * @param $tags
     * @param $userid
     */
    public function insert(string $title, string $text, int $category_id, array $tags, int $session)
    {
        $stmt = $this->pdo->prepare('insert into submission_form (title, text, date, category_id, user_id) values(?, ?, now(), ?, ?)');
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->bindParam(2, $text, PDO::PARAM_STR);
        $stmt->bindParam(3, $category_id, PDO::PARAM_STR);
        $stmt->bindParam(4, $session, PDO::PARAM_STR);

        $stmt->execute();
        $tagform = $this->pdo->lastInsertId('id');

//    投稿を記録する(中間テーブル)

        foreach ($tags as $val) {
            $stmt = $this->pdo->prepare('insert into form_tag (form_id, tag_id) values(?, ?)');
            $stmt->bindParam(1, $tagform, PDO::PARAM_STR);
            $stmt->bindParam(2, $val, PDO::PARAM_STR);
            $stmt->execute();

        }
    }

    /**
     * @param $session
     * @return array
     */
    public function select_category(int $session)
    {
        $statement = $this->pdo->prepare("SELECT * FROM category where user_id_category = ?");

        //bindValueメソッドでパラメータをセット
        $statement->bindValue(1, $session, PDO::PARAM_INT);

        //executeでクエリを実行
        $statement->execute();
        $rows = $statement->fetchAll();

        $this->pdo = null;

        return $rows;

    }

    /**
     * @param $session
     * @return array
     */
    public function select_tag(int $session)
    {
        $statements = $this->pdo->prepare("SELECT * FROM tag where user_id = ?");
        //bindValueメソッドでパラメータをセット
        $statements->bindValue(1, $session, PDO::PARAM_INT);

        //executeでクエリを実行
        $statements->execute();
        $rowss = $statements->fetchAll();
        $this->pdo = null;

        return $rowss;
    }

}
