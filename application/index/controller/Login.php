<?php
namespace app\index\controller;
 
use think\Controller;
 
class Login extends Controller
{
    public function index()
    {
    	return $this->fetch();
    }   
      // 处理登录逻辑
    public function doLogin()
    {
    	$param = input('post.');
    	if(empty($param['user_name'])){
    		
    		$this->error('用户名不能为空');
    	}
    	
    	if(empty($param['user_pwd'])){
    		
    		$this->error('密码不能为空');
    	}
    	
    	// 验证用户名
    	$has = db('users')->where('user_name', $param['user_name'])->find();
    	if(empty($has)){
    		
    		$this->error('用户名密码错误');
    	}
    	
    	// 验证密码
    	if($has['user_pwd'] != md5($param['user_pwd'])){
    		
    		$this->error('用户名密码错误');
    	}
    	
    	// 记录用户登录信息
    	cookie('user_id', $has['id'], 3600);  // 一个小时有效期
    	cookie('user_name', $has['user_name'], 3600);
    	
    	$this->redirect(url('index/index'));
    }
  
  /**用户注册方法
     * 参数：username,password
     **/
    public function register() {
        $param = input('post.');
    	if(empty($param['user_name'])){
    		
    		$this->error('用户名不能为空');
    	}
    	
    	if(empty($param['user_pwd'])){
    		
    		$this->error('密码不能为空');
    	}
        if( preg_match('/\'\/^\\s*$|^c:\\\\con\\\\con$|[%,\\*\\"\\s\\t\\<\\>\\&\'\\\\]/', $param['user_name']) ) {
            $this->error('用户名格式不正确');
        }
      
      // 检查用户是否已经注册过
    	$has = db('users')->where('user_name', $param['user_name'])->find();
    	if($has){
    		
    		$this->error('该用户名已经注册');
    	}
      //检查密码和确认密码是否一致
      if($param['user_pwd'] != $param['confirm']){
            $this->error('两次密码输入不一致');
      }

      //记录用户注册信息
        $data = [       //接受传递的参数  
                'user_name' => input('user_name'),  
                'user_pwd' => md5(input('user_pwd')),  
            ];  
        if(Db('users') -> insert($data)){        //添加数据  
                return $this->success('注册成功',"index",5); //成功后跳转登录界面  
            }
          //	$this->success("注册成功！",);


    }
}