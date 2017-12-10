<?php

namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class IndexController extends CommonController {
	
    public function main(){
        $user = M('user');
//        if($_SESSION['manager']==2){
            $user_res= $user->select();
//        }
        $this->assign('re',$user_res);
        $this->display();
    }


   public function  userlist(){
       $user = M('user');
       $res =$user->order('id DESC')->select();
       $this->assign('re',$res);
       $this->display();
   }

    public function editeuser(){
        $user = M('user');
        $res =$user->where(array('id'=>$_GET['id']))->select();
        if($_POST){
            $user->where(array(array('id'=>$_GET['id'])))->save(array('name'=>$_POST['name'],'phone'=>$_POST['phone']));
            echo "<script>window.location.href = '".__ROOT__."/index.php/Admin/Index/userlist';</script>";
        }
        $this->assign('res',$res[0]);
        $this->display();
    }

    public function deleteuser(){
        $user = M('user');
        $res =$user->where(array('id'=>$_GET['id']))->delete();
        echo "<script>window.location.href = '".__ROOT__."/index.php/Admin/Index/userlist';</script>";
    }

    public function adduser(){
        $user = M('user');
        if($_POST['name']){
            $res =$user->add(array('name'=>$_POST['name'],'phone'=>$_POST['phone']));
            echo "<script>window.location.href = '".__ROOT__."/index.php/Admin/Index/userlist';</script>";
        }
        $this->display();
    }

    public function downtlist(){
        $log = M('log');
        $res =$log->order('logid DESC')->select();
        $this->assign('res',$res);
        $this->display();
    }
}