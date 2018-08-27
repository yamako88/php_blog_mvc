<?php
/**
 * Created by PhpStorm.
 * User: yamauchiayaka
 * Date: 2018/08/17
 * Time: 14:56
 */

class PostValidation
{

    /**
     * @param $title
     * @param $text
     * @param $category_id
     * @param $tags
     * @return array
     */
    public function addValidation(string $title, string $text, string $category_id, array $tags)
    {
        $error = [];

        if (empty($title)) {
            $error['title'] = 'blank';
        } else {
            $error['title'] = null;
        }

        if (empty($text)) {
            $error['text'] = 'blank';
        } else {
            $error['text'] = null;
        }

        if ($category_id == "選択してください") {
            $error['category_id'] = 'blank';
        } else {
            $error['category_id'] = null;
        }

        if (empty($tags)) {
            $error['tags'] = 'blank';
        } else {
            $error['tags'] = null;
        }

        return $error;

    }

}
