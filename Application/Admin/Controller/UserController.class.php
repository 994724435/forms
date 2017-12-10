<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller {
	public function login(){
        if(IS_POST){
            $name = I('post.name');
            $pwd = I('post.pwd');
//            $user = M('user');
//            $result= $user->where(array('name'=>$name,'password'=>$pwd))->select();
            if($name=='admin'&&$pwd=="123asd"){
                $_SESSION['uname']=$name;
                echo "<script>window.location.href = '".__ROOT__."/index.php/Admin/Index/main';</script>";
            }else{
                    echo "<script>alert('用户名或密码不存在');";
                    echo "window.history.go(-1);";
                    echo "</script>";
                }
        }
        $this->display();
    }

    public function logOut(){
        session('uname',null);
        cookie('is_login',null);
        echo "<script>window.location.href = '".__ROOT__."/index.php/Admin/User/login';</script>";
    }

    public function crontabdog(){
        $orderlog =M('orderlog');
        $orders = $orderlog->where(array('type'=>2,'states'=>0,'productid'=>8))->select();

        foreach($orders as $k=>$v){
            $oldtime = strtotime($v['addtime']);
            $cha = time()-$oldtime;

            // 处理小狗长大
            $shouldtimemin = 86400*15;
            $shouldtimemax= 86400*30;
            if($cha>$shouldtimemin&&$cha<$shouldtimemax){
                $orderlog->where(array('logid'=>$v['logid']))->save(array('productid'=>93));
                continue;
            }

            // 处理狗狗生娃
//            if($cha>=$shouldtimemax){
//
//            }
//            $day =$v['life'];
//            $time =bcmul($day,86400);
//            if($cha>=$time){
//                $orderlog->where(array('logid'=>$v['logid']))->save(array('states'=>4));
//                print_r($v['logid']);
//            }

        }
        echo 'ok';
    }

    public function dealbigdog(){
        $orderlog =M('orderlog');
        $orders = $orderlog->where(array('type'=>2,'states'=>0,'productid'=>93))->select();

        foreach($orders as $k=>$v){
            $oldtime = strtotime($v['addtime']);
            $cha = time()-$oldtime;

            // 处理小狗长大
            $shouldtimemax= 86400*30;

            // 处理狗狗生娃
            if($cha>=$shouldtimemax){
                $isdog = $orderlog->where(array('type'=>2,'land'=>$v['logid']))->select();
                if($isdog[0]){
                    continue;
                }
                //新增小狗
                $v['land'] =$v['logid'];
                $v['logid'] =0;
                $v['productid'] =8;
                $orderlog->add($v);
            }

            $shoudie =86400*60;
            if($cha>=$shoudie){
                $orderlog->where(array('logid'=>$v['logid']))->save(array('states'=>4));
                print_r($v['logid']);
            }
        }
        echo 'ok';
    }

    // 每小时减少狗的能量
    public function incrodog(){
        $orderlog =M('orderlog');
        $orders = $orderlog->where(array('type'=>2,'states'=>0,'productid'=>8))->select();
        // 减少狗的能量
        foreach($orders as $k=>$v){
           $nengliang =  bcsub($v,10);
           $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$nengliang));
        }

        $orders = $orderlog->where(array('type'=>2,'states'=>0,'productid'=>93))->select();
        // 减少狗的能量
        foreach($orders as $k=>$v){
            $nengliang =  bcsub($v,10);
            $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$nengliang));
        }

    }

    public function crontab(){  //更新任务
        $orderlog =M('orderlog');
        $orders = $orderlog->where(array('type'=>3,'states'=>1))->select();
        $action =M('action');
        foreach($orders as $k=>$v){

            $oldtime = strtotime($v['addtime']);
            $cha = time()-$oldtime;
            $day = floor(bcdiv($cha,86400,2));

            if($day<1){
                $hour = floor(bcdiv($cha,3600,2));
                if($hour<1){
                    continue;
                }

                if($hour>=1&&$hour<3){  // 处理发芽 浇水
                    $min = floor(bcdiv($cha,60,2));
                    if($v['iswater']==1){
                        $action_res = $action->where(array('logid'=>$v['logid'],'state'=>1))->select();
                        if($action_res[0]){
                            foreach($action_res as $val){
                                $action_cha =time()-strtotime($val['addtime']);
                                $action_min = floor(bcdiv($action_cha,60,2));
                                if($action_min>=60&&$action_min<=70){
                                    $output =bcmul($v['output'],1.05,2);
                                    $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output));
                                    $action->where(array('id'=>$val['id']))->save(array('state'=>0));
                                }
                            }

                            continue;
                        }
                    }else{
                        if($min>=60&&$min<=70){   // 没浇水的
                            $output =bcmul($v['output'],0.95,2);
                            $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output,'iswater'=>1));

                        }else{
                            continue;
                        }
                    }
                    continue;
                }


                if($hour>=6&&$hour<7){  // 处理二次除草
                    if($v['ischucao']==2){
                        $output =bcmul($v['output'],0.9,2);  // tu do 算法
                        $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output,'ischucao'=>1));
                        continue;
                    }
                }

                // 处理长草 除草
                if($hour>=3&&$hour<8){
                    $min = floor(bcdiv($cha,60,2));
                    if($v['ischucao']==1){
                        $action_res = $action->where(array('logid'=>$v['logid'],'state'=>1))->select();
                        if($action_res[0]){
                            foreach($action_res as $val){
                                $action_cha =time()-strtotime($val['addtime']);
                                $action_min = floor(bcdiv($action_cha,60,2));
                                if($action_min>=180&&$action_min<=190){
                                    $output =bcmul($v['output'],1,2);
                                    $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output));
                                    $action->where(array('id'=>$val['id']))->save(array('state'=>0));
                                }
                            }

                            continue;
                        }
                    }else{
                        // 开始长草
                        if($min>=180&&$min<=190){
                            if($v['ischucao']!=1){
                                $orderlog->where(array('logid'=>$v['logid']))->save(array('iscao'=>1));
                            }
                        }
                        // 10 min 未除草减产
                        if($min>=190&&$min<=200){
                            $output =bcmul($v['output'],0.9,2);
                            if($v['ischucao']!=2){
                                $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output,'ischucao'=>2));
                            }
                        }
                    }

                    continue;
                }

                // 处理二次长虫
                if($hour>=20&&$hour<23){
                    if($v['ischuchong']==2){
                        $output =bcmul($v['output'],0.9,2);  // tu do 算法
                        $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output,'ischuchong'=>1));
                        continue;
                    }

                }
                // 处理长虫
                if($hour>=17&&$hour<23){
                    $min = floor(bcdiv($cha,60,2));
                    if($v['ischuchong']==1){
                        $action_res = $action->where(array('logid'=>$v['logid'],'state'=>1))->select();
                        if($action_res[0]){
                            foreach($action_res as $val){
                                $action_cha =time()-strtotime($val['addtime']);
                                $action_min = floor(bcdiv($action_cha,60,2));
                                if($action_min>=1020&&$action_min<=1440){
                                    $output =bcmul($v['output'],1,2);
                                    $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output));
                                    $action->where(array('id'=>$val['id']))->save(array('state'=>0));
                                }
                            }

                            continue;
                        }
                    }else{
                        // 开始长虫
                        if($min>=1020&&$min<=1440){
                            if($v['ischong']!=1){
                                $orderlog->where(array('logid'=>$v['logid']))->save(array('ischong'=>1));
                            }
                        }

                        // 10 min 未除虫减产
                        if($min>=1030&&$min<=1040){
                            $output =bcmul($v['output'],0.8,2);
                            if($v['ischuchong']!=2){
                                $orderlog->where(array('logid'=>$v['logid']))->save(array('output'=>$output,'ischuchong'=>2));
                            }
                        }
                    }
                    continue;
                }

                if($hour>23&&$hour<=24){ // 成熟
                    $orderlog->where(array('logid'=>$v['logid']))->save(array('states'=>2));
                }
            }else{
                echo '大于一天';
            }
        }
        echo '成功';
    }


    public function dealcsv(){
        $urls ='http://mydev.com/2017zaokou/1.csv';
        $iscsv = substr($urls,-3);
        if($iscsv!="csv"){
            throw new Exception('不支持的文件格式,请使用csv');
        }

        $file = fopen($urls, 'r');
        if ($file === false){
            throw new Exception('打开文件错误', 0);
        }
        fgetcsv($file);//这个不能删
        $tmpData = fgetcsv($file);//第一行不算，取第二行

        while ($tmpData){
            $is_empty = true;
            foreach ($tmpData as $key => $value){
                $encode = mb_detect_encoding($value, array("ASCII", "GB2312", "GBK", 'UTF-8'));//注意这个顺序，可能性大的排在前面
                $value = iconv($encode, 'UTF-8', $value);
                $tmpData[$key] = $value;
                if ($value) {
                    $is_empty = false;
                }
            }
            if (!$is_empty) {
                $dataList[] = $tmpData;
            }
            $tmpData = fgetcsv($file);

        }
        $user = M('user');

        foreach($dataList as $k=>$v){
            $data =array();
            $data['name'] = $v[0];
            $data['phone'] =$v[1];
            $user->add($data);
        }
        print_r(1);die;
    }

}



 ?>