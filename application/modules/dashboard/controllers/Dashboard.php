<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MX_Controller
{
	function __construct() {
		parent::__construct();
	    $this->load->model('Mdl_dashboard');
	    $this->load->model('member/Mdl_member');
	    $this->load->model('doctor/Mdl_doctor');
	    $this->load->model('banner/Mdl_banner');
	    $this->load->model('blog/Mdl_blog');
	    $this->load->model('media/Mdl_media');
	    $this->load->model('forum/Mdl_forum');
	    $this->load->model('notifications/Mdl_notifications');
	}

	/*************************************** Admin Dashboard **************************************/
	function admin(){
		if(!Modules::run('site_security/isAdmin')){
			redirect('admin/login','refresh');
		}
		
		$template = 'admin';
		$data = array();
		$data['viewFile'] = 'admin-dashboard';
		$data['page'] = 'home';
		echo Modules::run('template/'.$template, $data); 
	}
	
/*****************************************************************************************************************************************************************************************************************************************************************************************
												Memeber Dashboard 	
*****************************************************************************************************************************************************************************************************************************************************************************************/
	
	function member(){
		if(!Modules::run('site_security/isMember')){
			redirect('login','refresh');
		}
		
		$id = $this->session->userdata('userId'); 
		$mem = $this->Mdl_member->getMemmberDetails($id);
		
		foreach ($mem as $row) {
			$userId = $row->regId;
			$type = $row->type;
			$name = $row->name;	
			$image = $row->profileImage;	
			$doclist = $row->followedDocs;
		}
		
		$sessionData = array(
							'userId'=>$userId,
							'type'=>$type,
							'name'=>$name,
							'image'=>$image,
							'doclist'=>$doclist
						);
						
		$user = $this->session->set_userdata('type',$type);
		$this->session->set_userdata('userData', $sessionData);
		
		$data = array();
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
					$sub_array[] = "No";
				}
				$featuredDoc[] = $sub_array;
			}
		}else{
			$featuredDoc = "No Data";
		}
		
		$template = 'member';
		$data['docList'] = $featuredDoc;
		$data['banner'] = $this->Mdl_banner->getMemberBanner();
		$data['module'] = 'member';
		$data['viewFile'] = 'dashboard';
		$data['scriptFile'] = 'member-dashboard';
		$data['home'] = '1';
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** Member Dashboard Content **************************************/
	function getMemberContent(){
		$page =  $_GET['page'];
		$record =  5;
		$start = ($page - 1) * $record;
		
		$uid = $this->session->userdata('userId');
		$utype = $this->session->userdata('type');
		
		$mem = $this->Mdl_member->getMemmberDetails($uid);
		
		$cat = $mem[0]->fieldsOfInterest; 			
		$followers = $mem[0]->followedDocs;
		
		$categoryList = explode(",",$cat);
		$specialityList = '';
			
		foreach($categoryList as $cat){
			if($specialityList !== ''){
				$specialityList .= ','.$this->Mdl_forum->getSpecialityByCategoryId($cat);
			}else{
				$specialityList .= $this->Mdl_forum->getSpecialityByCategoryId($cat);
			}
		}
		
		$baseurl = base_url();
		
		$memData = array();
		
		$dashBlog = $this->Mdl_blog->memberDashboard($cat, $followers, $start, $record);
		$dashMedia = $this->Mdl_media->memberDashboard($cat, $followers, $start, $record);
		$dashForum = $this->Mdl_forum->memberDashboard($specialityList, $followers, $start, $record);
		
		$i = 0;
		$memData = array();
		
		if($dashBlog !== 'no'){
			foreach($dashBlog as $blog){
				if($blog->postedBy == 'admin'){
					$name = 'Just Medical Advice';
					$url = 'admin_assets/images/JMA.png';
					$profileUrl = 'javascript:;';
				}else if($blog->postedBy == 'doc'){
					$docData = $this->Mdl_blog->getDoctorDetails($blog->userId);
					$name = $docData[0]->name;
					$url = $docData[0]->profileImage;
					$did = base64_encode($docData[0]->regId);
					$did = str_replace(str_repeat('=',strlen($did)/4),"",$did);
					$profileUrl = base_url().'doctor/view/'.$did;
				}
				
				
				$likes = $this->Mdl_blog->getLikeCount($blog->blogId);
				$comments = $this->Mdl_blog->getCommentCount($blog->blogId);
				$isLiked = $this->Mdl_blog->isLiked($blog->blogId,$utype,$uid);
						
				if($blog->image == 'No Data'){
					$memData[$i] = '<div class="db_article_box fade_anim">
										<div class="row articleDetails">
											<div class="col-xs-12 author_info">
												<div class="author_dp">
													<a href="'.$profileUrl.'"><img src="'.$baseurl.''.$url.'"></a>
												</div>
												<div class="author_name">
													<strong>
														<a href="'.$profileUrl.'" class="txtblue">'.$name.'</a>
													</strong> shared article
												</div>
											</div>
											<div class="col-xs-12 article_info">
												<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($blog->createdDate)).'</div>
												<a href="'.$baseurl.'blog/view/'.$blog->slug.'" class="txtblue"><h2>'.$blog->title.'</h2></a>
												<p class="">'.substr($blog->shortDesc,0,180).'...</p>
												<div class="article_stats">
													<div class="counts"><span class="blogging_icons thank"></span> '.$likes.' Likes</div>
													<div class="counts"><span class="blogging_icons comment"></span> '.$comments.' Comments</div>
												</div>
											</div>
										</div>
										<div class="row blogactivity">
											<div class="col-sm-3 col-xs-6 text-center">
												<button class="" onclick="addBlogLike('.$blog->blogId.')")><span id="'.$blog->blogId.'" class="blogging_icons ';
												if($isLiked == 1){
													$memData[$i] .= 'thanked';
												}else{
													$memData[$i] .= 'thank';
												}
											$memData[$i] .='"></span> Thank</button>
											</div>
											<div class="col-sm-3 col-xs-6 text-center">
												<a href="'.$baseurl.'blog/view/'.$blog->slug.'/#share"><button><span class="blogging_icons share"></span>Share</button></a>
											</div>
											<div class="col-sm-6 col-xs-12">
												<a href="'.$baseurl.'blog/view/'.$blog->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
											</div>
										</div>
									</div>';
				}else{
					$memData[$i] =  '<div class="db_article_box fade_anim">
										<div class="row articleDetails">
											<div class="col-sm-6 col-xs-12 author_info">
												<div class="author_dp">
													<a href="'.$profileUrl.'"><img src="'.$baseurl.''.$url.'"></a>
												</div>
												<div class="author_name">
													<strong>
														<a href="'.$profileUrl.'" class="txtblue">'.$name.'</a>
													</strong> shared article
												</div>
											</div>
											<div class="col-sm-6 col-xs-12 article_pic">
												<a href="'.$baseurl.'blog/view/'.$blog->slug.'" class="img_holder">
													<img src="'.$baseurl.'admin_assets/images/blog/'.$blog->image.'">
												</a>
											</div>
											<div class="col-sm-6 col-xs-12 article_info">
												<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($blog->createdDate)).'</div>
												<a href="'.$baseurl.'blog/view/'.$blog->slug.'" class="txtblue"><h2>'.$blog->title.'</h2></a>
												<p class="">'.substr($blog->shortDesc,0,120).'...</p>
												<div class="article_stats">
													<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
													<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
												</div>
											</div>
										</div>
										<div class="row blogactivity">
											<div class="col-sm-3 col-xs-6 text-center">
												<button class="" onclick="addBlogLike('.$blog->blogId.')")><span id="'.$blog->blogId.'" class="blogging_icons ';
												if($isLiked == 1){
													$memData[$i] .= 'thanked';
												}else{
												$memData[$i] .= 'thank';
												}
											$memData[$i] .='"></span> Thank</button>
											</div>
											<div class="col-sm-3 col-xs-6 text-center">
												<a href="'.$baseurl.'blog/view/'.$blog->slug.'/#share"><button><span class="blogging_icons share"></span>Share</button></a>
											</div>
											<div class="col-sm-6 col-xs-12">
												<a href="'.$baseurl.'blog/view/'.$blog->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
											</div>
										</div>
									</div>';
				}
				$i = $i + 3;
			}
		}
		
		$i = 1;
		if($dashMedia !== 'no'){
			foreach($dashMedia as $media){
				if($media->postedBy == 'admin'){
					$name = 'Just Medical Advice';
					$url = 'admin_assets/images/JMA.png';
					$profileUrl = 'javascript:;';
				}else if($media->postedBy == 'doc'){
					$docData = $this->Mdl_media->getDoctorDetails($media->userId);
					$name = $docData[0]->name;
					$url = $docData[0]->profileImage;
					$did = base64_encode($docData[0]->regId);
					$did = str_replace(str_repeat('=',strlen($did)/4),"",$did);
					$profileUrl = base_url().'doctor/view/'.$did;
				}
				
				$likes = $this->Mdl_media->getLikeCount($media->mediaId);
				$comments = $this->Mdl_media->getCommentCount($media->mediaId);
				$isLiked = $this->Mdl_media->isLiked($media->mediaId,$utype,$uid);

				$str = '';
				$str .= '<div class="db_article_box fade_anim">
									<div class="row articleDetails">
										<div class="col-sm-6 col-xs-12 author_info">
											<div class="author_dp">
												<a href="'.$profileUrl.'">
													<img src="'.base_url().$url.'">
												</a>
											</div>
											<div class="author_name">
												<strong><a href="'.$profileUrl.'" class="txtblue">'.$name.'</a></strong> shared video
											</div>
										</div>';
					
					if($media->ctype == 'youtube'){	
						$str .=  '<div class="col-sm-6 col-xs-12 article_pic">
									<div class="img_holder">
										<iframe src="'.$media->url.'?rel=0&autoplay=0&showinfo=0&controls=0" frameborder="0" allowfullscreen></iframe>
									</div>
								</div>';
					}else if($media->ctype == 'upload'){
						$str .= '<div class="col-sm-6 col-xs-12 article_pic">
									<div class="img_holder">
										<video width="400" controls controlsList="nodownload">
											<source src="'.$media->url.'">
											Your browser does not support HTML5 video.
										</video>
									</div>
								</div>'; 
					}else{
						$str .= '<div class="col-sm-6 col-xs-12">
									<div class="img_holder" style="text-align:center; margin-top:60px;">
										<audio controls controlsList="nodownload">
											<source src="'.$media->url.'">
											Your browser does not support HTML5 video.
										</audio>
									</div>
								</div>';
					}
					
					$str .= '<div class="col-sm-6 col-xs-12 article_info">
									<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($media->createdDate)).'</div>
									<a href="'.base_url().'media/view/'.$media->slug.'" class="txtblue">
										<h2>'.$media->title.'</h2>
									</a>
									<p class="">'.substr($media->description,0,120).'...</p>
									<div class="article_stats">
										<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
										<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
									</div>
								</div>
							</div>
							<div class="row blogactivity">
								<div class="col-sm-3 col-xs-6 text-center">
									<button class="" onclick="addMediaLike('.$media->mediaId.')")><span id="'.$media->mediaId.'" class="blogging_icons ';
									if($isLiked == 1){
										$str .= 'thanked';
									}else{
										$str .= 'thank';
									}
									$str .='"></span> Thank</button>
								</div>
								<div class="col-sm-3 col-xs-6 text-center">
									<a href="'.$baseurl.'media/view/'.$media->slug.'/#share"><button><span class="blogging_icons share"></span> Share</button></a>
								</div>
								<div class="col-sm-6 col-xs-12">
									<a href="'.$baseurl.'media/view/'.$media->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
								</div>        
							</div>
						</div>';
				
				$memData[$i] = $str;
				$i = $i + 3;
			}
		}
		
		$i = 2;
		
		if($dashForum !== 'no'){
			foreach($dashForum as $forum){
				if($forum->postedBy == 'admin'){
					$name = 'Just Medical Advice';
					$url = base_url().'admin_assets/images/JMA.png';
					$profileUrl = 'javascript:;';
				}else if($forum->postedBy == 'doc'){
					$docData = $this->Mdl_blog->getDoctorDetails($forum->userId);
					$name = $docData[0]->name;
					$url = base_url().$docData[0]->profileImage;
					$did = base64_encode($docData[0]->regId);
					$did = str_replace(str_repeat('=',strlen($did)/4),"",$did);
					$profileUrl = base_url().'doctor/view/'.$did;
				}else if($forum->postedBy == 'mem'){
					$mData = $this->Mdl_forum->getMemberDetails($forum->userId);
					$name = $mData[0]->name;
					$url = base_url().$mData[0]->profileImage;
					$profileUrl = 'javascript:;';
				}
				
				$answers = $this->Mdl_forum->getAnswerCount($forum->forumId);
				
				$memData[$i] = '<div class="db_article_box fade_anim">
				<div class="row articleDetails">
					<div class="col-xs-12 author_info">
						<div class="author_dp">
							<a href="'.$profileUrl.'">
								<img src="'.$url.'">
							</a>
						</div>
						<div class="author_name">
							<strong>
								<a href="'.$profileUrl.'" class="txtblue">'.$name.'</a>
							</strong> asked
						</div>
					</div>
            
					<div class="col-xs-12 article_info">
						<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($forum->createdDate)).'</div>
						<a href="'.base_url().'forum/view/'.$forum->slug.'" class="txtblue">
							<h2>'.$forum->question.'</h2>
						</a>
						<div class="article_stats">
							<div class="counts"><span class="blogging_icons comment"></span> '.$answers.' answers</div>
						</div>
					</div>
				</div>
        
				<div class="row blogactivity">
					<div class="col-xs-6 text-center"><a href="'.base_url().'forum/view/'.$forum->slug.'/#share"><button><span class="blogging_icons share"></span> Share</button></a></div>
					<div class="col-xs-6"><a href="'.base_url().'forum/view/'.$forum->slug.'/#comment" class="form-control commentanchor txtdark">Write a answer</a></div>        
				</div>
			</div>';
			
				$i = $i + 2;
			}
		}
		
		
		$finalData = '';
		for($j=0; $j<15; $j++){
			if(array_key_exists($j,$memData)){
				$finalData .= $memData[$j];
			}
		}
		
		if($finalData !== ''){
			echo $finalData; 
		}else{
			echo 1; exit;
		}
	}
	
	/*************************************** Dashboard Search **************************************/
	function memDashSearch(){
		$search = $_POST['searchKey'];
		$data = explode(" ",$search);
		
		$msg = "";
		
		if(!empty($search)){
			$blogCat = $this->Mdl_blog->getCategoryBySearch($data);
			$mediaCat = $this->Mdl_media->getCategoryBySearch($data);
			$specialities = $this->Mdl_forum->getSpecialityBySearch($data);
			
			if(is_array($blogCat)){
		        $msg.="<div class='result_box'><div class='result_tl'>Blogs based On Category</div>";
                foreach($blogCat as $val){
                    $msg.="<a href='".base_url()."blog/searches/".$val->catSlug."'>".$val->catName."</a>";
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($mediaCat)){
		        $msg.="<div class='result_box'><div class='result_tl'>Audio/Video based On Category</div>";
                foreach($mediaCat as $val){
                    $msg.="<a href='".base_url()."media/searches/".$val->catSlug."'>".$val->catName."</a>";
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($specialities)){
		        $msg.="<div class='result_box'><div class='result_tl'>Forums based On Speciality</div>";
                foreach($specialities as $val){
                    $msg.="<a href='".base_url()."forum/searches/".$val->spSlug."'>".$val->spName."</a>";
                }
                $msg.="</div>"; 
		    }
			
			if($blogCat=="no" && $mediaCat=="no" && $specialities=="no"){
		      $msg.="<div class='result_box'><p style='margin-left: 10px;'>No results found for <b><span style='color:red'>$search</span></b>. </p></div>";
			}
			
			echo $msg;
		}else{
			echo 1;
		}
	}

/*****************************************************************************************************************************************************************************************************************************************************************************************
														Doctor Functionality 	
*****************************************************************************************************************************************************************************************************************************************************************************************/

	/*************************************** Dashboard **************************************/
	function doctor(){
		if(!Modules::run('site_security/isDoctor')){
			redirect('login','refresh');
		}
		
		$uid = $this->session->userdata('userId');
		$doc = $this->Mdl_doctor->getDoctorDetails($uid);
		
		foreach ($doc as $row) {
			$type = $row->type;
			$name = $row->name;	
			$image = $row->profileImage;
			$speciality = $row->speciality; 			
			$degree = $row->degree;
			$followers = $row->followedMem;
		}
		
		$sessionData = array(
							'userId'=>$uid,
							'type'=>$type,
							'name'=>$name,
							'image'=>$image,
							'speciality'=>$speciality,
							'degree'=>$degree,
							'followedMem'=>$followers
						);
		$user = $this->session->set_userdata('type',$type);
		$this->session->set_userdata('userData', $sessionData);
		
		$template = 'doctor';
		$data = array();
		$data['banner'] = $this->Mdl_banner->getDoctorBanner();
		$data['blogCount'] = $this->Mdl_blog->getDoctorBlogCount($uid);
		$data['videoCount'] = $this->Mdl_media->getDoctorMediaCount($uid);
		
		$findForums = $this->Mdl_forum->getForumsForDocs($speciality,$followers);
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
		$data['module'] = 'doctor';
		$data['viewFile'] = 'dashboard';
		$data['scriptFile'] = 'doctor-dashboard';
		$data['home'] = '1';
		echo Modules::run('template/'.$template, $data);
	}
	
	/*************************************** Dashboard Content**************************************/
	function getDoctorContent(){
		$page =  $_GET['page'];
		$record = 1;
		$start = ($page - 1) * $record;
		
		$uid = $this->session->userdata('userId');
		$utype = $this->session->userdata('type');
		
		$doc = $this->Mdl_doctor->getDoctorDetails($uid);
		$speciality = $doc[0]->speciality; 			
		$followers = $doc[0]->followedMem;
		
		$finalData = '';
		$baseurl = base_url();
		
		$resBlog = $this->Mdl_blog->doctorDashboard($start,$record);
		if($resBlog !== 'no'){
			if($resBlog[0]->postedBy == 'admin'){
				$name = 'Just Medical Advice';
				$url = 'admin_assets/images/JMA.png';
			}else{
				$docData = $this->Mdl_blog->getDoctorDetails($resBlog[0]->userId);
				$name = $docData[0]->name;
				$url = $docData[0]->profileImage;
			}
			
			$likes = $this->Mdl_blog->getLikeCount($resBlog[0]->blogId);
			$comments = $this->Mdl_blog->getCommentCount($resBlog[0]->blogId);
			$isLiked = $this->Mdl_blog->isLiked($resBlog[0]->blogId,$utype,$uid);
			
			if($resBlog[0]->image == 'No Data'){
				$finalData = '<div class="db_article_box fade_anim">
									<div class="row articleDetails">
										<div class="col-xs-12 author_info">
											<div class="author_dp">
												<a href="javascript:;"><img src="'.$baseurl.''.$url.'"></a>
											</div>
											<div class="author_name">
												<strong>
													<a href="javascript:;" class="txtblue">'.$name.'</a>
												</strong> shared article
											</div>
										</div>
										<div class="col-xs-12 article_info">
											<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resBlog[0]->createdDate)).'</div>
											<a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'" class="txtblue"><h2>'.$resBlog[0]->title.'</h2></a>
											<p class="">'.substr($resBlog[0]->shortDesc,0,180).'...</p>
											<div class="article_stats">
												<div class="counts"><span class="blogging_icons thank"></span> '.$likes.' Likes</div>
												<div class="counts"><span class="blogging_icons comment"></span> '.$comments.' Comments</div>
											</div>
										</div>
									</div>
									<div class="row blogactivity">
										<div class="col-sm-3 col-xs-6 text-center">
											<button class="" onclick="addBlogLike('.$resBlog[0]->blogId.')")><span id="'.$resBlog[0]->blogId.'" class="blogging_icons ';
											if($isLiked == 1){
												$finalData .= 'thanked';
											}else{
												$finalData .= 'thank';
											}
										$finalData .='"></span> Thank</button>
										</div>
										<div class="col-sm-3 col-xs-6 text-center">
											<a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'/#share"><button><span class="blogging_icons share"></span>Share</button></a>
										</div>
										<div class="col-sm-6 col-xs-12">
											<a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
										</div>
									</div>
								</div>';
			}else{
				$finalData =  '<div class="db_article_box fade_anim">
									<div class="row articleDetails">
										<div class="col-sm-6 col-xs-12 author_info">
											<div class="author_dp">
												<a href="javascript:;"><img src="'.$baseurl.''.$url.'"></a>
											</div>
											<div class="author_name">
												<strong>
													<a href="javascript:;" class="txtblue">'.$name.'</a>
												</strong> shared article
											</div>
										</div>
										<div class="col-sm-6 col-xs-12 article_pic">
											<a href="javascript:;" class="img_holder">
												<img src="'.$baseurl.'admin_assets/images/blog/'.$resBlog[0]->image.'">
											</a>
										</div>
										<div class="col-sm-6 col-xs-12 article_info">
											<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resBlog[0]->createdDate)).'</div>
											<a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'" class="txtblue"><h2>'.$resBlog[0]->title.'</h2></a>
											<p class="">'.substr($resBlog[0]->shortDesc,0,120).'...</p>
											<div class="article_stats">
												<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
												<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
											</div>
										</div>
									</div>
									<div class="row blogactivity">
										<div class="col-sm-3 col-xs-6 text-center">
											<button class="" onclick="addBlogLike('.$resBlog[0]->blogId.')")><span id="'.$resBlog[0]->blogId.'" class="blogging_icons ';
											if($isLiked == 1){
												$finalData .= 'thanked';
											}else{
												$finalData .= 'thank';
											}
										$finalData .='"></span> Thank</button>
										</div>
										<div class="col-sm-3 col-xs-6 text-center">
											<a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'/#share"><button><span class="blogging_icons share"></span>Share</button></a>
										</div>
										<div class="col-sm-6 col-xs-12">
											<a href="'.$baseurl.'blog/read/'.$resBlog[0]->slug.'/#comment" class="form-control commentanchor txtdark">Write a comment</a>
										</div>
									</div>
								</div>';
			}
		}else{
			$record = 2;
		}
		
		$resMedia = $this->Mdl_media->doctorDashboard($start,$record);
		if($resMedia !== 'no'){
			if($resMedia[0]->postedBy == 'admin'){
				$name = 'Just Medical Advice';
				$url = 'admin_assets/images/JMA.png';
			}else{
				$docData = $this->Mdl_media->getDoctorDetails($resMedia[0]->userId);
				$name = $docData[0]->name;
				$url = $docData[0]->profileImage;				
			}
			
			$likes = $this->Mdl_media->getLikeCount($resMedia[0]->mediaId);
			$comments = $this->Mdl_media->getCommentCount($resMedia[0]->mediaId);
					
			$finalData .= '<div class="db_article_box fade_anim">
								<div class="row articleDetails">
									<div class="col-sm-6 col-xs-12 author_info">
										<div class="author_dp">
											<a href="">
												<img src="'.base_url().$url.'">
											</a>
										</div>
										<div class="author_name">
											<strong><a href="" class="txtblue">'.$name.'</a></strong> shared video
										</div>
									</div>';
				
				if($resMedia[0]->ctype == 'youtube'){	
					$finalData .=  '<div class="col-sm-6 col-xs-12 article_pic">
								<div class="img_holder">
									<iframe src="'.$resMedia[0]->url.'" frameborder="0" allowfullscreen></iframe>
								</div>
							</div>';
				}else if($resMedia[0]->ctype == 'upload'){
					$finalData .= '<div class="col-sm-6 col-xs-12 article_pic">
								<div class="img_holder">
									<video width="400" controls controlsList="nodownload">
										<source src="'.$resMedia[0]->url.'">
										Your browser does not support HTML5 video.
									</video>
								</div>
							</div>'; 
				}else{
					$finalData .= '<div class="col-sm-6 col-xs-12">
								<div class="img_holder" style="text-align:center; margin-top:60px;">
									<audio controls controlsList="nodownload">
										<source src="'.$resMedia[0]->url.'">
										Your browser does not support HTML5 video.
									</audio>
								</div>
							</div>';
				}
				
				$finalData .= '<div class="col-sm-6 col-xs-12 article_info">
								<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resMedia[0]->createdDate)).'</div>
								<a href="'.base_url().'media/watch/'.$resMedia[0]->slug.'" class="txtblue">
									<h2>'.$resMedia[0]->title.'</h2>
								</a>
								<p class="">'.substr($resMedia[0]->description,0,120).'...</p>
								<div class="article_stats">
									<div class="counts"><span class="blogging_icons thank"></span>'.$likes.' Thanks</div>
									<div class="counts"><span class="blogging_icons comment"></span>'.$comments.' Comments</div>
								</div>
							</div>
						</div>
						<div class="row blogactivity">
							<div class="col-sm-3 col-xs-6 text-center">
								<button class=""><span class="blogging_icons thank"></span> Thank</button>
							</div>
							<div class="col-sm-3 col-xs-6 text-center">
								<button><span class="blogging_icons share"></span> Share</button>
							</div>
							<div class="col-sm-6 col-xs-12">
								<a href="" class="form-control commentanchor txtdark">Write a comment</a>
							</div>        
						</div>
					</div>';
		}else{
			$record = 3;
		}
		
		$resForum = $this->Mdl_forum->doctorDashboard($speciality, $followers, $start, $record);
		if($resForum !== 'no'){
			if($resForum[0]->postedBy == 'admin'){
				$name = 'Just Medical Advice';
				$url = base_url().'admin_assets/images/JMA.png';
			}else if($resForum[0]->postedBy == 'doc'){
				$docData = $this->Mdl_forum->getDoctorDetails($resForum[0]->userId);
				$name = $docData[0]->name;
				$url = base_url().$docData[0]->profileImage;					
			}else{
				$memData = $this->Mdl_forum->getMemberDetails($resForum[0]->userId);
				$name = $memData[0]->name;
				$url = base_url().$memData[0]->profileImage;
			}
				
			$answers = $this->Mdl_forum->getAnswerCount($resForum[0]->forumId);
				
			$finalData .= '<div class="db_article_box fade_anim">
				<div class="row articleDetails">
					<div class="col-xs-12 author_info">
						<div class="author_dp">
							<a href="">
								<img src="'.$url.'">
							</a>
						</div>
						<div class="author_name">
							<strong>
								<a href="" class="txtblue">'.$name.'</a>
							</strong> asked
						</div>
					</div>
            
					<div class="col-xs-12 article_info">
						<div class="articleDate txtdark">'.date("d-M, Y ",strtotime($resForum[0]->createdDate)).'</div>
						<a href="'.base_url().'forum/read/'.$resForum[0]->slug.'" class="txtblue">
							<h2>'.$resForum[0]->question.'</h2>
						</a>
						<div class="article_stats">
							<div class="counts"><span class="blogging_icons comment"></span> '.$answers.' answers</div>
						</div>
					</div>
				</div>
        
				<div class="row blogactivity">
					<div class="col-xs-6 text-center"><a href="'.base_url().'forum/read/'.$resForum[0]->slug.'/#share"><button><span class="blogging_icons share"></span> Share</button></a></div>
					<div class="col-xs-6"><a href="'.base_url().'forum/read/'.$resForum[0]->slug.'/#comment" class="form-control commentanchor txtdark">Write a answer</a></div>        
				</div>
			</div>';
		}
		
		if($resBlog == 'no' && $resMedia == 'no' && $resForum == 'no'){
			echo 1; exit;
		}else{
			echo $finalData;
		}
	}
	
	/*************************************** Dashboard Search **************************************/
	function docDashSearch(){
		$search = $_POST['searchKey'];
		$data = explode(" ",$search);
		
		$msg = "";
		
		if(!empty($search)){
			$blog = $this->Mdl_blog->searchBlogs($data);
			$media = $this->Mdl_media->searchMedia($data);
			$forum = $this->Mdl_forum->searchForum($data);

			if(is_array($blog)){
		        $msg.="<div class='result_box'><div class='result_tl'>Blogs Available</div>";
                foreach($blog as $val){
                    $msg.="<a href='".base_url()."blog/read/".$val->slug."'>".$val->title."</a>";
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($media)){
		        $msg.="<div class='result_box'><div class='result_tl'>Audio/Video Available</div>";
                foreach($media as $val){
                    $msg.="<a href='".base_url()."media/watch/".$val->slug."'>".$val->title."</a>";
                }
                $msg.="</div>"; 
		    } 
			
			if(is_array($forum)){
		        $msg.="<div class='result_box'><div class='result_tl'>Forums Available</div>";
                foreach($forum as $val){
                    $msg.="<a href='".base_url()."forum/read/".$val->slug."'>".$val->question."</a>";
                }
                $msg.="</div>"; 
		    }
			
			if($blog=="no" && $media=="no" && $forum=="no"){
		      $msg.="<div class='result_box'><p style='margin-left: 10px;'>No results found for <b><span style='color:red'>$search</span></b>. </p></div>";
			}
			
			echo $msg;
		}else{
			echo 1;
		}
	}
}