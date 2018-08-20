<?php

require_once('Model.php');

/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/20
 * Time: 12:10
 */
class BlogeditModel extends Model
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

//    選択してるタグの表示

    /**
     * @param $session
     * @param $postid
     * @return array
     */
    public function selecttag($session, $postid)
    {
        $stmt = $this->pdo->prepare("select form_id,tag_id,tag_name, user_id
        from (select * from form_tag where form_id=?) as form_tag left join
        (select * from tag where user_id=?)as tag on tag.id = form_tag.tag_id");
        //bindValueメソッドでパラメータをセット
        $stmt->bindValue(1, $postid);
        $stmt->bindValue(2, $session);


        //executeでクエリを実行
        $stmt->execute();
        $forms = $stmt->fetchAll();
        $this->pdo = null;

        return $forms;
    }

    /**
     * @param $session
     * @param $title
     * @param $text
     * @param $category_id
     * @param $form_id
     */
    public function update($session, $title, $text, $category_id, $form_id)
    {
        $stmt = $this->pdo->prepare('update submission_form set title = ?, text=?, date=now(), category_id=?, user_id=? where id = ?');
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->bindParam(2, $text, PDO::PARAM_STR);
        $stmt->bindParam(3, $category_id, PDO::PARAM_STR);
        $stmt->bindParam(4, $session, PDO::PARAM_STR);
        $stmt->bindParam(5, $form_id, PDO::PARAM_STR);

        $stmt->execute();
        $tagform = $this->pdo->lastInsertId('id');
//    投稿を記録する(中間テーブル)
        $tags = $_POST["tags"];


        $del = $this->pdo->prepare('DELETE FROM form_tag WHERE form_id=?');
        $del->bindValue(1, $form_id);
        $del->execute();

        foreach ($tags as $val) {
            $stmt = $this->pdo->prepare('insert into form_tag (form_id, tag_id) values(?, ?)');
            $stmt->bindParam(1, $form_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $val, PDO::PARAM_STR);
            $stmt->execute();
        }

    }


}