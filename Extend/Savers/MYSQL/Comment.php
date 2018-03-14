<?php 


namespace Stratum\Extend\Saver\MYSQL;

use Stratum\Original\Data\Data;
use Stratum\Original\Data\DatabaseQuerier;
use Stratum\Original\Data\Saver;

Class Comment extends MYSQL
{

    public function __construct(Data $data)
    {
        if ($data->date == '') {
            $data->date = date('Y-m-d H:i:s');
            $data->dateGMT = gmdate('Y-m-d H:i:s');
        }
        
        parent::__construct($data);
    }

    public function save()
    {
        if ($this->WordpressAPIExists()) {
            $this->dataWasSaved = $this->saveFromWordpressAPI();
        } else {
            parent::save();
        }   
    }

    protected function WordpressAPIExists()
    {
        return function_exists('wp_insert_comment');
    }

    protected function saveFromWordpressAPI()
    {
        return wp_insert_comment([
            'comment_post_ID' => $this->data->postId,
            'comment_author' => $this->data->authorName,
            'comment_author_email' => $this->data->authorEmail,
            'comment_author_url' => $this->data->authorUrl,
            'comment_content' => $this->data->content,
            'comment_type' => $this->data->type,
            'comment_parent' => $this->data->parentId,
            'user_id' => $this->data->userId,
            'comment_author_IP' => $this->data->authorIp,
            'comment_agent' => $this->data->userAgent,
            'comment_date' => date('Y-m-d H:i:s'),
            'comment_approved' => $this->data->approvalStatus,
        ]);
    }   





}