<?php

require_once('Model.php');

/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/20
 * Time: 14:17
 */
class SearchModel extends Model
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
     * @param $search
     * @return array
     */
    public function search($session, $search)
    {
        $statement = $this->pdo->prepare("select * from (SELECT * FROM submission_form where user_id = ? order by id desc) as forms 
        left join category ON category.category_id = forms.category_id 
        left join (select form_id, tag_id, group_concat(tag_name separator ',') as tag_name, user_id from (select * from form_tag) as form_tag 
        left join tag on tag.id = form_tag.tag_id group by form_id) as tags on tags.form_id = forms.id 
        where CONCAT(title, text, date, category_name, tag_name) LIKE '%$search%'");
        //bindValueメソッドでパラメータをセット
        $statement->bindValue(1, $session);
        $statement->execute();
        $rows = $statement->fetchAll();

        return $rows;
    }

}