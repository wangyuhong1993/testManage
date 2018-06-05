<?php
	class UserController extends BaseController{
		function detailAction(){
			// 删除一个用户
			$id = $_GET['id'];
			$obj = ModelFactory::M('UserModel');
			$data = $obj->GetUserInfoById($id);
			// 载入用户视图;
			include VIEW_PATH.'userInfo.html';
		}
		function showFormAction(){
			// 新增用户
			include  VIEW_PATH.'showForm.html';
		}
		function addUserAction(){
			// 接受表单数据;
			$user_name = $_POST['username'];
			$user_pass = $_POST['password'];
			$age = $_POST['age'];
			$xueli = $_POST['xueli'];
			$aihao = $_POST['aihao'];
			$xingqu = implode(',', $aihao); // 将数组的所有项,用英文逗号'，'连接起来 
			$from = $_POST['from'];

			$obj = ModelFactory::M('UserModel');
			$result = $obj->InsertUser($user_name,$user_pass,$age,$xueli,$xingqu,$from);
			$this->goToUrl('添加用户成功','?',5);
		}
		function delAction(){
			// 删除一个用户
			$id = $_GET['id'];
			$obj = ModelFactory::M('UserModel');
			$result = $obj->delUserById($id);
			$this->goToUrl('删除用户成功','?',5);
		}
		function editAction(){
			$id = $_GET['id'];
			// 从数据库中取出该用户的所有数据信息
			$obj = ModelFactory::M('UserModel');
			$user = $obj->GetUserInfoById($id);
			// 兴趣数据，需要单独处理-->数组
			$aihao = explode(',', $user['xingqu']); 
			include  VIEW_PATH.'user_form_view.html';
		}
		function updateAction(){
			// 接受表单数据;
			$id = $_POST['id'];
			$user_name = $_POST['username'];
			$user_pass = $_POST['password'];
			$age = $_POST['age'];
			$xueli = $_POST['xueli'];
			$aihao = $_POST['aihao'];
			$xingqu = implode(',', $aihao); // 将数组的所有项,用英文逗号'，'连接起来 
			$from = $_POST['from'];

			$obj = ModelFactory::M('UserModel');
			$result = $obj->UpdateUser($id,$user_name,$user_pass,$age,$xueli,$xingqu,$from);
			$this->goToUrl('修改用户成功','?',5);
		}
		function indexAction(){
			// 转化通过单例去实现
			$obj_user = ModelFactory::M('UserModel'); 
			$data1 = $obj_user->GetAllUser();
			$data2 = $obj_user->GetUserCount(); 
			// 载入视图文件,以显示该2份数据;
			include  VIEW_PATH.'showAllUser_view.html';
		}
	}
?>