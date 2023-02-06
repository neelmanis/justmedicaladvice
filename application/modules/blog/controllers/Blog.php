<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('Mdl_blog');	
		$this->load->model('doctor/Mdl_doctor');
		$this->load->model('forum/Mdl_forum');
		$this->load->model('notifications/Mdl_notifications');
	}

/*****************************************************************************************************************************************************************************************************************************************************************************************														Admin Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	
	/******************************************* Blog Listing *******************************************/ 
	function blogList(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$blog = $this-> Mdl_blog->listAll();
		if($blog !== "No Data"){
			$blogData = array();
			foreach($blog as $val){
				$res = array();
				$id = base64_encode($val->blogId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$res['id'] = $id;
				$cat = $this->Mdl_blog->findCategoryName($val->categoryId);
				if($cat == 'no'){
					$res['catName'] = "No Data";
				}else{
					$res['catName'] = $cat[0]->catName;
				}
				$res['title'] = $val->title;
				$res['slug'] = $val->slug;
				$res['postedBy'] = $val->postedBy;
				$res['name'] = $this->Mdl_blog->findName($val->postedBy,$val->userId);
				$res['report'] = $this->Mdl_blog->countReport($val->blogId);
				$res['visible'] = $val->visibleTo;
				$res['status'] = $val->isActive;
				$res['isHome'] = $val->isHome;
				$res['createdDate'] = $val->createdDate;
				$blogData[] = $res;
			}
			$data['blogList'] = $blogData;
		}else{
			$data['blogList'] = "No Data";
		}
		
		$data['viewFile'] = "list-blog";
		$data['page'] = 'listBlog';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Active Action ***************************************/
	function activeAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$this->Mdl_blog->statusActive($bid);
			redirect('blog/list-blog');	
		}
	}
	
	/***************************************** Inactive Action ***************************************/
	function inActiveAction($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$this->Mdl_blog->statusInactive($bid);
			redirect('blog/list-blog');	
		}
	}
	
	/***************************************** Blog Set Home ***************************************/
	function setHome($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$this->Mdl_blog->setHome($bid);
			redirect('blog/list-blog');	
		}
	}
	
	/***************************************** Blog Unset Home ***************************************/
	function unsetHome($id){
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$this->Mdl_blog->unsetHome($bid);
			redirect('blog/list-blog');	
		}
	}
	
	/***************************************** Delete Blog ***************************************/
	function deleteBlog(){
		$bid = $_POST['blogId'];
		$base_64 = $bid . str_repeat('=', strlen($bid) % 4);
		$bId = base64_decode($base_64);
		$delete = $this->Mdl_blog->deleteById($bId);
		echo $delete;
	}
	
	/***************************************** Add Blog ***************************************/
	function newBlog(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$data['category'] = $this->Mdl_blog->getCategory();
        $data['viewFile'] = 'add-blog';
		$data['page'] = 'addBlog';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Add Blog Action ***************************************/
	function addBlogAction(){
		$content = $this->input->post();

        $this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title</b> field is required'
		));
		$this->form_validation->set_rules("category","Category","required|xss_clean",array(
					'required'      => '<b>Category</b> is not selected'
		));
		$this->form_validation->set_rules("subcategory","Subcategory","required|xss_clean",array(
					'required'      => '<b>Subcategory</b> is not selected'
		));
		$this->form_validation->set_rules("content","Description","required",array(
					'required'      => '<b>Description</b> field is required'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
		
			$find = array("/","_","?","(",")","-",":","!","'",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['title']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/','',$new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
				
			$urlCheck = $this->Mdl_blog->urlCheck($urlKey);
			
			if($urlCheck){  
				if(!empty($_FILES['image']['name'])){
					$blogimg = $_FILES['image']['name'];
					$imgname=str_replace(array(" ","'"),"_",time().$blogimg);
					$img = $this->uploadFile($imgname,"image","blog");	
					if($img !== 1){
						echo $img; exit;
					}
				}else{
					$imgname = "No Data";
				}
				
				if($content['reference'] !== ''){
					$links = $content['reference'];
				}else{
					$links = "No Data";
				}
				
				$data = array(
					'title' => $content['title'],
					'slug' => $urlKey,
					'categoryId' => $content['subcategory'],
					'content'=> $content['content'],
					'shortDesc'=>strip_tags($content['content']),
					'reference'=> $links,
					'postedBy' => $this->session->type,
					'userId' => $this->session->userdata('userId'),
					'image'=>$imgname,
					'visibleTo'=>$content['visible'],
					'isActive' => '1',
					'createdDate' =>date('Y-m-d H:i:s'),
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
				
				$insert = $this->Mdl_blog->insert($data);
				
				// Add Notification
				if($this->session->type == 'admin'){
					$name = "Just Medical Advice";
					$image = base_url().'admin_assets/images/JMA.png';
					$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' posted a blog - “'.$content['title'].'” <div class="date">'.date("d M Y ").'</div>';
				
					$data1 = array(
						'notification' => $text,
						'url' => base_url().'blog/view/'.$urlKey,
						'allMembers' => '1',
						'userId' => 0,
						'createdDate' => date('Y-m-d H:i:s'),
						'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
					);
					
					$this->Mdl_notifications->insert("notification_members",$data1);
					
					$data2 = array(
						'notification' => $text,
						'url' => base_url().'blog/read/'.$urlKey,
						'allDoctors' => '1',
						'userId' => 0,
						'createdDate' => date('Y-m-d H:i:s'),
						'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
					);
					
					$this->Mdl_notifications->insert("notification_doctors",$data2);
					
				}else if($this->session->type == 'doc'){
					$doc = $this->Mdl_blog->getDoctorDetails($this->session->userdata('userId'));
					$name = $doc[0]->name;
					$image = base_url().$doc[0]->profileImage;
					$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' posted a blog - “'.$content['title'].'” <div class="date">'.date("d M Y ").'</div>';
				
					$data1 = array(
						'notification' => $text,
						'url' => base_url().'blog/view/'.$urlKey,
						'allMembers' => '1',
						'userId' => 0,
						'createdDate' => date('Y-m-d H:i:s'),
						'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
					);
					
					$this->Mdl_notifications->insert("notification_members",$data1);
				}
				
				echo 1; exit;
			}else{
				echo 2; exit;
			}
		}
	}
	
	/***************************************** Edit Blog ***************************************/
	function editBlog($id){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		if(!empty($id)){
			$base_64 = $id . str_repeat('=', strlen($id) % 4);
			$bid = base64_decode($base_64);
			$data['category'] = $this->Mdl_blog->getCategory();
			$blog = $this->Mdl_blog->findById($bid);			
			$data['categoryId'] = $this->Mdl_blog->findCategoryId($blog[0]->categoryId);
			$data['getData'] = $blog;
			$subCat = $this->Mdl_blog->getSubCategory($data['categoryId']);
			$str = '<option disabled>Choose one</option>';
			$selected = $blog[0]->categoryId;
			if(is_array($subCat)){
				foreach($subCat as $val){
					if($selected == $val->catId){
						$str .= '<option value="'.$val->catId.'"selected >'.$val->catName.'</option>';
					}else{
						$str .= '<option value="'.$val->catId.'">'.$val->catName.'</option>';
					} 
				}
			}
			$data['subCategory'] = $str;
			$data['viewFile'] = "edit-blog";
			$data['page'] = "editBlog";
			$template = 'admin';
			echo Modules::run('template/'.$template, $data);	
		}
	}	

	/***************************************** Edit Blog Action ***************************************/
	function editBlogAction(){
		$content = $this->input->post();
		
        $this->form_validation->set_rules("title","Title","required|xss_clean",array(
					'required'      => '<b>Title **</b> field is required'
		));
		$this->form_validation->set_rules("category","Category","required|xss_clean",array(
					'required'      => '<b>Category **</b> is not selected'
		));
		$this->form_validation->set_rules("subcategory","Subcategory","required|xss_clean",array(
					'required'      => '<b>Subcategory **</b> is not selected'
		));
		$this->form_validation->set_rules("content","Description","required",array(
					'required'      => '<b>content **</b> field is required'
		));
		$this->form_validation->set_rules("visible","Visible To","required|xss_clean",array(
					'required'      => '<b>Select Visible Type</b>'
		));
		$this->form_validation->set_rules("status1","Status","required|xss_clean",array(
					'required'      => '<b>Status **</b> field is not selected'
		));
		
		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			
			$find = array("/","_","?","(",")","-",":","!","'",".",",","\r","\n","\r\n");
			$replace = array("");
			$new_string = str_replace($find,$replace,strtolower($content['title']));
			$new_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_string); 
			$urlKey = str_replace(" ","-",trim($new_string));
			
			if($urlKey !== $content['slug']){
				$urlCheck = $this->Mdl_blog->urlCheck($urlKey);
			}else{
				$urlCheck = TRUE;
			}
			
			if($urlCheck){  
				if(!empty($_FILES['image']['name'])){
					$blogimg = $_FILES['image']['name'];
					$imgname=str_replace(array(" ","'"),"_",time().$blogimg);
					$img = $this->uploadFile($imgname,"image","blog");
					if($img !== 1){
						echo $img; exit;
					}
				}else{
					$imgname = $content['imgName']; 
				}
				
				if($content['reference'] !== ''){
					$links = $content['reference'];
				}else{
					$links = "No Data";
				}
				
				$data = array(
					'title' => $content['title'],
					'slug' => $urlKey,
					'categoryId' => $content['subcategory'],
					'content'=> $content['content'],
					'shortDesc'=>strip_tags($content['content']),
					'reference'=> $links,
					'image'=>$imgname,
					'visibleTo'=>$content['visible'],
					'isActive' => $content['status1'],
					'modifiedDate' =>date('Y-m-d H:i:s')
				);
				
				$edit = $this->Mdl_blog->update($data,base64_decode($content['blogId']));
				echo 1; exit;
			}else{
				echo 2; exit;
			}
		}
	}
	
	/***************************************** View Blog ***************************************/
	function viewBlog($url){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$blog = $this->Mdl_blog->getBlogByUrl($url);
		$likes = $this->Mdl_blog->getLikeCount($blog[0]->blogId);
		$comments = $this->Mdl_blog->getCommentCount($blog[0]->blogId);
		$data['blogDetails'] = $blog;
		$data['likeCount'] = $likes;
		$data['commentCount'] = $comments;
		$data['isLiked'] = $this->Mdl_blog->isLiked($blog[0]->blogId,$this->session->type,$this->session->userdata('userId'));
		$data['isReport'] = $this->Mdl_blog->isReport($blog[0]->blogId,$this->session->type,$this->session->userdata('userId'));
		
		if($blog[0]->postedBy == 'admin'){
			$userData = $this->Mdl_blog->getAdminDetails($blog[0]->userId);
		}else{
			$userData = $this->Mdl_blog->getDoctorDetails($blog[0]->userId);
		}
		 
		$comment = $this->show_tree($blog[0]->blogId);
		if($comment == 'no'){
			$data['comments'] = '';
		}else{
			$data['comments'] = $comment;
		}
		
		$data['userDetails'] = $userData;
		$data['viewFile'] = 'view-blog';
		$data['page'] = 'viewBlog';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Flagged Comment Listing ***************************************/
	function flagComments(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin','refresh');
		}
		
		$report = $this-> Mdl_blog->getFlagCommentId();
		if($report !== 'No Data'){
			$comment = array();
			foreach($report as $re){
				$result = $this->Mdl_blog->getCommentById($re->commentId);
				if($result !== 'No Data'){
					$set = array();
					$set['commentId'] = $result[0]->commentId;
					$set['comment'] = $result[0]->comment;
					$set['isActive'] = $result[0]->isActive;
					$comment[] = $set;
				}
			}
		}else{
			$comment = 'No Data';
		}
		$data['comments'] = $comment;
		$data['viewFile'] = "list-blog-comment";
		$data['page'] = 'blogFlagComments';
		$template = 'admin';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Blog Inactive Comment ***************************************/
	function inActiveComment($id){
		$result = $this->Mdl_blog->inActiveComment($id);
		redirect('blog/flag-comments');
	}
	
	/***************************************** Blog Active Comment ***************************************/
	function activeComment($id){
		$result = $this->Mdl_blog->activeComment($id);
		redirect('blog/flag-comments');
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Doctor Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/***************************************** Add Blog ***************************************/
	function writeArticle(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$data['category'] = $this->Mdl_blog->getCategory();
        $data['module'] = 'blog';
        $data['viewFile'] = 'write-an-article';
        $data['scriptFile'] = 'doctor-blog-create';
		$data['blog'] = '1';
		$template = 'doctor';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Doctor Read Blog ***************************************/
	function read($url){
		if(!Modules::run('site_security/isDoctor')){
				redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId');
		$type = $this->session->type;
		
		$blog = $this->Mdl_blog->getBlogByUrl($url);
		$likes = $this->Mdl_blog->getLikeCount($blog[0]->blogId);
		$comments = $this->Mdl_blog->getCommentCount($blog[0]->blogId);
		
		$doc = $this->Mdl_doctor->getDoctorDetails($id);
			
		$findForums = $this->Mdl_forum->getForumsForDocs($doc[0]->speciality,$doc[0]->followedMem);
		if($findForums != 'no'){
			$forums = array();
			foreach($findForums as $val){
				$final = array();
				$final['question'] = $val->question;
				$final['url'] = base_url().'forum/read/'.$val->slug;
				if($val->postedBy == 'admin'){
					$name = 'Just Medical Advice';
					$image = base_url().'admin_assets/images/JMA.png'; 
				}else if($val->postedBy == 'doc'){
					$user = $this->Mdl_forum->getDoctorDetails($val->userId);
					$name = $user[0]->name;
					$image = base_url().$user[0]->profileImage; 
				}else{
					$user = $this->Mdl_forum->getMemberDetails($val->userId);
					$name = $user[0]->name;
					$image = base_url().$user[0]->profileImage;
				}
				$final['uname'] = $name;
				$final['uimage'] = $image;
				$final['answer'] =  $this->Mdl_forum->getAnswerCount($val->forumId);
				$final['cdate'] = $val->createdDate;
				$forums[] = $final;
			}
		}else{
			$data['forumList'] = 'No Data';
		}
		$data['forumList'] = $forums;
		
		$data['scriptFile'] = 'doctor-blog';
		$data['blog'] = '1';
		$template = 'doctor';
		
		if($blog[0]->postedBy == 'admin'){
			$userData = $this->Mdl_blog->getAdminDetails($blog[0]->userId);
		}else{
			$userData = $this->Mdl_blog->getDoctorDetails($blog[0]->userId);
		}
		 
		$comment = $this->show_tree($blog[0]->blogId);
		if($comment == 'no'){
			$data['comments'] = '';
		}else{
			$data['comments'] = $comment;
		}
		
		$data['blogDetails'] = $blog;
		$data['likeCount'] = $likes;
		$data['commentCount'] = $comments;		
		$data['isLiked'] = $this->Mdl_blog->isLiked($blog[0]->blogId,$type,$id);
		$data['isReport'] = $this->Mdl_blog->isReport($blog[0]->blogId,$type,$id);
		$data['userDetails'] = $userData;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'view';
		$data['module'] = 'blog';
		
		echo Modules::run('template/'.$template, $data);
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Memeber Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	/***************************************** Blog Listing ***************************************/
	function listAll(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$result = $this->Mdl_doctor->getFeaturedDoctors();
		if($result !== 'No Data'){
			$featuredDoc = array();
			foreach($result as $res){
				$sub_array = array();
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$sub_array[] = $id;
				$img = $res->profileImage;
				$sub_array[] = $img;
				$name = $res->name;
				$sub_array[] = $name;
				$res2 = explode(",",$res->speciality);
				$sp = '';
				$size = sizeof($res2);
				
				if($size > 2){
					$size -= ($size-2);
				}
				
				for($i=0; $i< $size; $i++){
					$sp .= $this->Mdl_doctor->findSpecialityName($res2[$i]).'<br>';
				}
				$sub_array[] = $sp;
				$sub_array[] = $res->regId;
				if($res->followedMem !== ''){
					$sub_array[] = sizeof(explode(",",$res->followedMem));
				}else{
					$sub_array[] = "No ";
				}
				
				$featuredDoc[] = $sub_array;
			}
		}else{
			$featuredDoc = "No Data";
		}
		
		$data['docList'] = $featuredDoc;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'list';
		$data['scriptFile'] = 'member-blog-list';
		$data['module'] = 'blog';
		$data['blog'] = '1';
		$data['searchCat'] = '';
		$data['searchSubmit'] = '';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Get Blog List ***************************************/
	function getBlogList(){
		$page =  $_GET['page'];
		$search =  $_GET['searchCat'];
		$searchKeys = $_GET['searchSubmited'];
		$searchList =  explode(" ",$searchKeys);
		$record = 5;
		$start = ($page - 1) * $record;
		
		$uid = $this->session->userdata('userId');
		$utype = $this->session->userdata('type');
		
		if($utype == 'mem'){
			$udata = $this->Mdl_blog->getMemberDetails($uid);
			
			if($searchKeys !== ''){
				$result = $this->Mdl_blog->getSearchSubmit($searchList, $start, $record);
			}else if($search !== ''){
				$result = $this->Mdl_blog->getBlogsByCategory($search, $start, $record);
			}else{
				$result = $this->Mdl_blog->listForMembers($udata[0]->fieldsOfInterest, $udata[0]->followedDocs, $start, $record);
			}
			
			if($result !== "no"){
				$bloglisting = '';
				$baseurl = base_url();
				foreach($result as $res){
					if($res->postedBy == 'admin'){
						$name = 'Just Medical Advice';
						$url = 'admin_assets/images/JMA.png';
						$myprofile = 'javascript:;';
					}else{
						$docData = $this->Mdl_blog->getDoctorDetails($res->userId);
						$name = $docData[0]->name;
						$url = $docData[0]->profileImage;
						$did = base64_encode($docData[0]->regId);
						$did = str_replace(str_repeat('=',strlen($did)/4),"",$did);
						$myprofile = base_url().'doctor/view/'.$did;
					}
					$likes = $this->Mdl_blog->getLikeCount($res->blogId);
					$comments = $this->Mdl_blog->getCommentCount($res->blogId);
					$isLiked = $this->Mdl_blog->isLiked($res->blogId,$utype,$uid);
					
					if($res->image == 'No Data'){
						$bloglisting .= '<div class="db_article_box fade_anim">
											<div class="row articleDetails">
												<div class="col-xs-12 author_info">
													<div class="author_dp">
														<a href="javascript:;"><img src="'.$baseurl.''.$url.'"></a>
													</div>
													<div class="author_name">
														<strong>
															<a href="'.$myprofile.'" class="txtblue">'.$name.'</a>
														</strong> shared article
													</div>
												</div>
												<div class="col-xs-12 article_info">
													<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($res->createdDate)).'</div>
													<a href="'.$baseurl.'blog/view/'.$res->slug.'" class="txtblue"><h2>'.$res->title.'</h2></a>
													<p class="">'.substr($res->shortDesc,0,180).'...</p>
													<div class="article_stats">
														<div class="counts"><span class="blogging_icons thank"></span> '.$likes.' Likes</div>
														<div class="counts"><span class="blogging_icons comment"></span> '.$comments.' Comments</div>
													</div>
												</div>
											</div>
											<div class="row blogactivity">
												<div class="col-sm-3 col-xs-6 text-center">
													<button class="" onclick="addBlogLike('.$res->blogId.')")><span id="'.$res->blogId.'" class="blogging_icons ';
													if($isLiked == 1){
														$bloglisting .= 'thanked';
													}else{
														$bloglisting .= 'thank';
													}
												$bloglisting .='"></span> Thank</button>
												</div>
												<div class="col-sm-3 col-xs-6 text-center">
													<a href="'.$baseurl.'blog/view/'.$res->slug.'/#share"><button><span class="blogging_icons share"></span>Share</button></a>
												</div>
												<div class="col-sm-6 col-xs-12">
													<a href="'.$baseurl.'blog/view/'.$res->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
												</div>
											</div>
										</div>';
					}else{
						$bloglisting .=  '<div class="db_article_box fade_anim">
											<div class="row articleDetails">
												<div class="col-sm-6 col-xs-12 author_info">
													<div class="author_dp">
														<a href="javascript:;"><img src="'.$baseurl.''.$url.'"></a>
													</div>
													<div class="author_name">
														<strong>
															<a href="'.$myprofile.'" class="txtblue">'.$name.'</a>
														</strong> shared article
													</div>
												</div>
												<div class="col-sm-6 col-xs-12 article_pic">
													<a href="'.$baseurl.'blog/view/'.$res->slug.'" class="img_holder">
														<img src="'.$baseurl.'admin_assets/images/blog/'.$res->image.'">
													</a>
												</div>
												<div class="col-sm-6 col-xs-12 article_info">
													<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($res->createdDate)).'</div>
													<a href="'.$baseurl.'blog/view/'.$res->slug.'" class="txtblue"><h2>'.$res->title.'</h2></a>
													<p class="">'.substr($res->shortDesc,0,120).'...</p>
													<div class="article_stats">
														<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
														<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
													</div>
												</div>
											</div>
											<div class="row blogactivity">
												<div class="col-sm-3 col-xs-6 text-center">
													<button class="" onclick="addBlogLike('.$res->blogId.')")><span id="'.$res->blogId.'" class="blogging_icons ';
													if($isLiked == 1){
														$bloglisting .= 'thanked';
													}else{
														$bloglisting .= 'thank';
													}
												$bloglisting .='"></span> Thank</button>
												</div>
												<div class="col-sm-3 col-xs-6 text-center">
													<a href="'.$baseurl.'blog/view/'.$res->slug.'/#share"><button><span class="blogging_icons share"></span>Share</button></a>
												</div>
												<div class="col-sm-6 col-xs-12">
													<a href="'.$baseurl.'blog/view/'.$res->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
												</div>
											</div>
										</div>';
					}					
				}
				echo $bloglisting;
			}else{
				echo 1;
			}
		}
	}
	
	/***************************************** View Blog ***************************************/
	function view($url){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId');
		$type = $this->session->type;
		
		$blog = $this->Mdl_blog->getBlogByUrl($url);
		if($blog == 'no'){
			redirect('errors');
		}
		
		$likes = $this->Mdl_blog->getLikeCount($blog[0]->blogId);
		$comments = $this->Mdl_blog->getCommentCount($blog[0]->blogId);
		
		$findSimilar = $this->Mdl_blog->getsuggestedBlogs($blog[0]->categoryId,$blog[0]->postedBy,$blog[0]->userId,$blog[0]->blogId);
		if($findSimilar !== 'no'){
			$relatedBlogs = array();
			foreach($findSimilar as $val){
				$final = array();
				$final['title'] = $val->title;
				$final['url'] = base_url().'blog/view/'.$val->slug;
				if($val->image == 'No Data'){
					$imagePath = '';
				}else{
					$imagePath = base_url() . 'admin_assets/images/blog/'.$val->image;
				}
				$final['image'] = $imagePath;
				$final['like'] =  $this->Mdl_blog->getLikeCount($val->blogId);
				$relatedBlogs[] = $final;
			}
		}else{
			$relatedBlogs = "No Data";
		}
		
		$data['suggestedBlog'] = $relatedBlogs;
		$data['scriptFile'] = 'member-blog-view';
		$data['blog'] = '1';
		$template = 'member';
		
		
		if($blog[0]->postedBy == 'admin'){
			$userData = $this->Mdl_blog->getAdminDetails($blog[0]->userId);
		}else{
			$userData = $this->Mdl_blog->getDoctorDetails($blog[0]->userId);
		}
		 
		$comment = $this->show_tree($blog[0]->blogId);
		if($comment == 'no'){
			$data['comments'] = '';
		}else{
			$data['comments'] = $comment;
		}
		
		$data['blogDetails'] = $blog;
		$data['likeCount'] = $likes;
		$data['commentCount'] = $comments;		
		$data['isLiked'] = $this->Mdl_blog->isLiked($blog[0]->blogId,$type,$id);
		$data['isReport'] = $this->Mdl_blog->isReport($blog[0]->blogId,$type,$id);
		$data['userDetails'] = $userData;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'view';
		$data['module'] = 'blog';
		
		echo Modules::run('template/'.$template, $data);
	}
	
	/***************************************** Load Search Results Based on Key ***************************************/
	function search(){
		$search = $_POST['searchKey'];
		$data = explode(" ",$search);
		
		$msg = "";
		
		if(!empty($search)){
			$categories = $this->Mdl_blog->getCategoryBySearch($data);
			$blogs = $this->Mdl_blog->getBlogBySearch($data);
			
			if(is_array($categories)){
		        $msg.="<div class='result_box'><div class='result_tl'>Blogs based On Category</div>";
                foreach($categories as $val){
                    $msg.="<a href='".base_url()."blog/searches/".$val->catSlug."'>".$val->catName."</a>";
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($blogs)){
		        $msg.="<div class='result_box'><div class='result_tl'>Blogs Available</div>";
                foreach($blogs as $val){
                    $msg.="<a href='".base_url()."blog/view/".$val->slug."'>".$val->title."</a>";
                }
                $msg.="</div>"; 
		    }
			
			if($categories=="no" && $blogs=="no"){
		      $msg.="<div class='result_box'><p style='margin-left: 10px;'>No results found for <b><span style='color:red'>$search</span></b>. </p></div>";
			}
			echo $msg;
		}else{
			echo 1;
		}
	}
	
	/***************************************** Display Search Results ***************************************/
	function searches($key){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$result = $this->Mdl_doctor->getFeaturedDoctors();
		if($result !== 'No Data'){
			$featuredDoc = array();
			foreach($result as $res){
				$sub_array = array();
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$sub_array[] = $id;
				$img = $res->profileImage;
				$sub_array[] = $img;
				$name = $res->name;
				$sub_array[] = $name;
				$res2 = explode(",",$res->speciality);
				$sp = '';
				$size = sizeof($res2);
				
				if($size > 2){
					$size -= ($size-2);
				}
				
				for($i=0; $i< $size; $i++){
					$sp .= $this->Mdl_doctor->findSpecialityName($res2[$i]).'<br>';
				}
				$sub_array[] = $sp;
				$sub_array[] = $res->regId;
				if($res->followedMem !== ''){
					$sub_array[] = sizeof(explode(",",$res->followedMem));
				}else{
					$sub_array[] = "No ";
				}
				
				$featuredDoc[] = $sub_array;
			}
		}else{
			$featuredDoc = "No Data";
		}
		
		$data['docList'] = $featuredDoc;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		
		$data['viewFile'] = 'list';
		$data['scriptFile'] = 'member-blog-list';
		$data['module'] = 'blog';
		$data['blog'] = '1';
		$data['searchCat'] = $key;
		$data['searchSubmit'] = '';
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}

	/***************************************** Search Submit ***************************************/
	function searchSubmit(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$content = $this->input->post();
		
		$result = $this->Mdl_doctor->getFeaturedDoctors();
		if($result !== 'No Data'){
			$featuredDoc = array();
			foreach($result as $res){
				$sub_array = array();
				$id = base64_encode($res->regId);
				$id = str_replace(str_repeat('=',strlen($id)/4),"",$id);
				$sub_array[] = $id;
				$img = $res->profileImage;
				$sub_array[] = $img;
				$name = $res->name;
				$sub_array[] = $name;
				$res2 = explode(",",$res->speciality);
				$sp = '';
				$size = sizeof($res2);
				
				if($size > 2){
					$size -= ($size-2);
				}
				
				for($i=0; $i< $size; $i++){
					$sp .= $this->Mdl_doctor->findSpecialityName($res2[$i]).'<br>';
				}
				$sub_array[] = $sp;
				$sub_array[] = $res->regId;
				if($res->followedMem !== ''){
					$sub_array[] = sizeof(explode(",",$res->followedMem));
				}else{
					$sub_array[] = "No ";
				}
				
				$featuredDoc[] = $sub_array;
			}
		}else{
			$featuredDoc = "No Data";
		}
		
		$data['docList'] = $featuredDoc;
		$data['speciality'] = $this->Mdl_forum->getSpeciality();
		$data['viewFile'] = 'list';
		$data['scriptFile'] = 'member-blog-list';
		$data['module'] = 'blog';
		$data['blog'] = '1';
		$data['searchCat'] = '';
		$data['searchSubmit'] = $content['searchText'];
		$template = 'member';
		echo Modules::run('template/'.$template, $data);
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************														Common Functionality
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	/***************************************** Add Blog Like ***************************************/
	function addLike(){
		$content = $this->input->post();
		
		$uid = $content['user'];
		$utype = $content['type'];
		$bid = $content['blogId'];
		
		if($uid !== '' && $utype !== '' && $bid !== ''){
			$isExist = $this->Mdl_blog->isLiked($bid,$utype,$uid);
			if($isExist == 1){
				$this->Mdl_blog->deleteLike($uid,$utype,$bid);	
				echo 1;	exit;
			}else{
				$data = array(
					'blogId' => $bid,
					'userType' => $utype,
					'userId'=> $uid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_blog->insert_like($data);		
				
				$blog = $this->Mdl_blog->findById($bid);
				
				// Add Notification
				if($utype == 'mem' && $blog[0]->postedBy == 'doc'){
					$mem = $this->Mdl_blog->getMemberDetails($uid);
					$name = $mem[0]->name;
					$image = base_url().$mem[0]->profileImage;
					
					$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' like your blog - “'.$blog[0]->title.'” <div class="date">'.date("d M Y ").'</div>';

					$data1 = array(
						'notification' => $text,
						'url' => base_url().'blog/read/'.$blog[0]->slug,
						'allDoctors' => '0',
						'userId' => $blog[0]->userId,
						'createdDate' => date('Y-m-d H:i:s'),
						'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
					);
			
					$this->Mdl_notifications->insert("notification_doctors",$data1);
				}
				echo 1; exit;
			}		
		}else{
			echo "Some Error Occured"; exit;
		}
	}
	
	/***************************************** Add Blog Like Action ***************************************/
	function blogLikeAction(){
		$content = $this->input->post();

		$bid = $content['blogId'];
		$uid = $this->session->userdata('userId');
		$utype = $this->session->type;
		
		if($uid !== '' && $utype !== '' && $bid !== ''){	
			$isExist = $this->Mdl_blog->isLiked($bid,$utype,$uid);
			if($isExist == 1){
				$this->Mdl_blog->deleteLike($uid,$utype,$bid);		
				echo 2;	exit;
			}else{
				$data = array(
					'blogId' => $bid,
					'userType' => $utype,
					'userId'=> $uid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_blog->insert_like($data);		
				
				$blog = $this->Mdl_blog->findById($bid);
				
				// Add Notification
				if($utype == 'mem' && $blog[0]->postedBy == 'doc'){
					$mem = $this->Mdl_blog->getMemberDetails($uid);
					$name = $mem[0]->name;
					$image = base_url().$mem[0]->profileImage;
					
					$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' like your blog - “'.$blog[0]->title.'” <div class="date">'.date("d M Y ").'</div>';

					$data1 = array(
						'notification' => $text,
						'url' => base_url().'blog/read/'.$blog[0]->slug,
						'allDoctors' => '0',
						'userId' => $blog[0]->userId,
						'createdDate' => date('Y-m-d H:i:s'),
						'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
					);
			
					$this->Mdl_notifications->insert("notification_doctors",$data1);
				}
				echo 1; exit;
			}		
		}else{
			echo "Some Error Occured"; exit;
		}
	}
	
	/***************************************** Add Blog Report ***************************************/
	function blogReport(){
		$content = $this->input->post();
		
		$uid = $content['user'];
		$utype = $content['type'];
		$bid = $content['blogId'];
		
		if($uid !== '' && $utype !== '' && $bid !== ''){
			
			$isReported = $this->Mdl_blog->isReport($bid,$utype,$uid);
			if($isReported == 1){
				$this->Mdl_blog->deleteBlogReport($uid,$utype,$bid);
				echo 1;	exit;
			}else{
				$data = array(
					'blogId' => $bid,
					'userType' => $utype,
					'userId'=> $uid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_blog->insert_blog_report($data);		
				echo 1; exit;
			}		
		}else{
			echo "Some Error Occured"; exit;
		}
	}
	
	/***************************************** Add Comments ***************************************/
	function addComment(){
		$content = $this->input->post();
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules("comment","Comment","required|xss_clean",array(
					'required' => 'Comment field should not be empty' ));

		if($this->form_validation->run() == FALSE){
			echo validation_errors();
		}else{
			$data = array(
				'blogId' => $content['bid'],
				'comment' => $content['comment'],
				'postedBy' => $content['utype'],
				'userId'=> $content['uid'],
				'parentId'=> $content['pid'],
				'createdDate' =>date('Y-m-d H:i:s')
			);
			
			$insert = $this->Mdl_blog->insert_comment($data);
			
			$blog = $this->Mdl_blog->findById($content['bid']);
				
			// Add Notification
			if($content['utype'] == 'mem' && $blog[0]->postedBy == 'doc'){
				$mem = $this->Mdl_blog->getMemberDetails($content['uid']);
				$name = $mem[0]->name;
				$image = base_url().$mem[0]->profileImage;
				
				$text = '<div class="user_dp"><img src="'.$image.'"></div> '.$name.' commented on your blog - “'.$blog[0]->title.'” <div class="date">'.date("d M Y ").'</div>';

				$data1 = array(
					'notification' => $text,
					'url' => base_url().'blog/read/'.$blog[0]->slug,
					'allDoctors' => '0',
					'userId' => $blog[0]->userId,
					'createdDate' => date('Y-m-d H:i:s'),
					'endDate' => date('Y-m-d H:i:s',strtotime("+2 months"))
				);
		
				$this->Mdl_notifications->insert("notification_doctors",$data1);
			}
			
			echo 1; exit;
		}
	}
	
	/***************************************** Report Blog Comment ***************************************/
	function addReport(){
		$content = $this->input->post();
		
		$cid = $content['cid'];
		
		if($cid !== ''){
			$isExist = $this->Mdl_blog->isReported($cid);
			if($isExist == 1){
				//echo 1;	exit;
				$this->Mdl_blog->deleteCommentReport($cid);
				echo 1; exit;
			}else{
				$data = array(
					'commentId'=> $cid,
					'createdDate' =>date('Y-m-d H:i:s')
				);
				$insert = $this->Mdl_blog->insert_report($data);		
				echo 1; exit;
			}		
		}else{
			echo 3; exit;
		}
	}
	
	/***************************************** Get Comment Id ***************************************/
	function show_tree($bid){
        // create array to store all comments ids
        $store_all_id = array();
        // get all parent comments ids by using news id
        $id_result = $this->Mdl_blog->tree_all($bid);
        // loop through all comments to save parent ids $store_all_id array
		
		if($id_result !== 'no'){
			foreach ($id_result as $comment_id) {
				array_push($store_all_id, $comment_id['parentId']);
			}
			// return all hierarchical tree data from in_parent by sending
			//  initiate parameters 0 is the main parent,news id, all parent ids
			return  $this->in_parent(0,$bid, $store_all_id);
		}else{
			return "no";
		}
    }

    /***************************************** Get Comment Hierarchy ***************************************/
    function in_parent($in_parent,$bid,$store_all_id) {
        // this variable to save all concatenated html
        $html = "";
        if (in_array($in_parent,$store_all_id)) {
            $result = $this->Mdl_blog->tree_by_parent($bid,$in_parent);
            foreach ($result as $re){
				$type = $re['postedBy'];
				$uid = $re['userId'];
				$commentId = $re['commentId'];
				$comment = $re['comment'];
				$date = $re['createdDate'];
				$name = '';
				$url = '';
				$isReported = $this->Mdl_blog->isReported($commentId);
				
				if($type == 'admin'){
					$name = 'Just Medical Advice';
					$url = base_url().'admin_assets/images/JMA.png';
				}else if($type == 'doc'){
					$docData = $this->Mdl_blog->getDoctorDetails($uid);
					$name = $docData[0]->name;
					$url = base_url().$docData[0]->profileImage;
				}else if($type == 'mem'){
					$memData = $this->Mdl_blog->getMemberDetails($uid);
					$name = $memData[0]->name;
					$url = base_url().$memData[0]->profileImage;
				}
				
				if($type == 'doc'){
					$html .= '<div class="comment_box">
					<div class="author_dp"><div><img src="'.$url.'"></div></div>
					<div class="comment">
					<div class="comment_container">
					<div class="comment_holder doc_comment">
					<strong class="txtblue">'.$name.' -</strong> '.$comment.'
					</div>
					<div class="comment_detail"><span style="margin-right:15px;">'.date("d-M, Y ",strtotime($date)).'</span> <a href="javascript:;" class="reply" id="'.$commentId.'" >Reply</a>
					<a href="javascript:;" class="report" id="'.$commentId.'"><div class="ldl_box"><button class="like_btn"><span class="blogging_icons ';
					if($isReported == 1){$html .= 'flagged';}else{$html .= 'flag';}
					$html .= '"></span> Report Abuse</button></div></a>
					</div></div>';
					$html .= $this->in_parent($commentId, $bid, $store_all_id);
					$html .= '</div></div>';
				}else{
					$html .= '<div class="comment_box">
					<div class="author_dp"><div><img src="'.$url.'"></div></div>
					<div class="comment">
					<div class="comment_container">
					<div class="comment_holder">
					<strong class="txtblue">'.$name.' -</strong> '.$comment.'
					</div>
					<div class="comment_detail"><span style="margin-right:15px;">'.date("d-M, Y ",strtotime($date)).'</span> <a href="javascript:;" class="reply" id="'.$commentId.'">Reply</a>
					<a href="javascript:;" class="report" id="'.$commentId.'"><div class="ldl_box"><button class="like_btn"><span class="blogging_icons ';
					if($isReported == 1){$html .= 'flagged';}else{$html .= 'flag';}
					$html .= '"></span> Report Abuse</button></div></a>
					</div></div>';
					$html .= $this->in_parent($commentId,$bid, $store_all_id);
					$html .= '</div></div>';
				}
			}
        }
        return $html;
    }
	
	/***************************************** Get Sub-Category ***************************************/
	function subcategory(){
		$cid = $_POST['cid'];
		$data=$this->Mdl_blog->getSubCategory($cid);
		if(is_array($data)){
			foreach($data as $val){
				$str .= '<option value="'.$val->catId.'">'.$val->catName.'</option>'; 
			}
			echo $str;
		}else{
			echo "1";
		}
	}	
	
	/***************************************** File Upload ***************************************/
	function uploadFile($imageName,$key,$folderName){
		$config['file_name'] = $imageName;
		$config['upload_path'] = './admin_assets/images/'.$folderName; 
		$config['allowed_types'] = "jpg|png|jpeg|svg";
		$config['max_width']  = '3000';
		$config['max_height']  = '3000';
		
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		   
		if (!$this->upload->do_upload($key)){
			return $this->upload->display_errors();
		}else{
			return 1;
		} 
	}
}