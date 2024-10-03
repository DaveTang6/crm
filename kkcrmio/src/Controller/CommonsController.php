<?php
declare(strict_types=1);
namespace App\Controller;


class CommonsController extends AppController
{
    public function updateFile()
    {
        $this->loadComponent('Uploader');
        $re = $this->Uploader->upload();
        if ($re['status'] === 200) {
            $this->G->success('ok',$re['data']);
        } else {
            $this->G->error($re['msg']);
        }
    }

}
