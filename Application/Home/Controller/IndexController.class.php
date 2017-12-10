<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class IndexController extends Controller {

	public function index(){
		header('content-type:text/html;charset=utf-8');
		if($_POST['name']){
			if($_POST['name']&&$_POST['tel']){
				$user = M('user');
				$res_user = $user->where(array('phone'=>$_POST['it_phone']))->select();
				$data['name'] =$_POST['name'];
                $data['phone'] =$_POST['tel'];
                $data['birthday'] =$_POST['birthday'];
                $data['number'] =$_POST['number'];
                $data['addymd'] =date('Y-m-d',time());
                $data['dadtime'] =date('Y-m-d H:i:s',time());
                $user->add($data);
                echo "<script>window.location.href = '".__ROOT__."/index.php/Home/Index/success';</script>";
			}
		}
		$this->display();
	}
}