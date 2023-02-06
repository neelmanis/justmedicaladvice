<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/****************** Home Page Url **********************/
$route['about-us'] = "home/aboutUs";
$route['jma-for-doctors'] = "home/jmaForDoctors";
$route['jma-for-members'] = "home/jmaForMembers";
$route['faq'] = "faq";
$route['contact-us'] = "home/contactUs";
$route['terms-and-conditions'] = "home/termsOfUse";
$route['privacy-policy'] = "home/privacy";
$route['signup'] = "registration/signup";
$route['forgot-password'] = "login/forgotPassword";
$route['registration/set-password'] = "registration/setPassword";


/****************** Admin Url **********************/
$route['admin/list-admin'] = "admin/listAdmin";
$route['admin/add-admin'] = "admin/addAdmin";
$route['admin/active-action/(:any)'] = "admin/activeAction/$1";
$route['admin/inactive-action/(:any)'] = "admin/inactiveAction/$1";
$route['admin/edit-admin/(:any)'] = "admin/editAdmin/$1";

$route['member/list-member'] = "member/listMember";

$route['doctor/list-doctor'] = "doctor/listDoctor";
$route['doctor/add-doctor'] = "doctor/addDoctor";

$route['speciality/list-speciality'] = "speciality/listall";
$route['speciality/add-speciality'] = "speciality/addSpeciality";
$route['speciality/edit-speciality/(:any)'] = "speciality/editSpeciality/$1";

$route['category/add-category'] = "category/addCategory";
$route['category/edit-category/(:any)'] = "category/editCategory/$1";

$route['blog/list-blog'] = "blog/blogList";
$route['blog/flag-comments'] = "blog/flagComments";

$route['media/list-media'] = "media/mediaList";
$route['media/flag-comments'] = "media/flagComments";

$route['forum/list-forum'] = "forum/forumList";
$route['forum/flag-comments'] = "forum/flagComments";

$route['degree/list-degree'] = "degree/listDegree";
$route['degree/add-degree'] = "degree/addDegree";
$route['degree/edit-degree/(:any)'] = "degree/editDegree/$1";

$route['location/country-list'] = "location/countryList";
$route['location/state-list'] = "location/stateList";
$route['location/city-list/(:any)'] = "location/listByCountry/$1";

$route['member/list-doctor'] = "doctor/listDoctor";
$route['member/list-doctor-request'] = "member/listDoctorRequest";
$route['member/list-featured-doctor'] = "member/listFeaturedDoctor";

$route['banner/list-banner'] = "banner/listBanner";

$route['event/list-event'] = "event/listEvent";
$route['event/add-event'] = "event/addEvent";
$route['event/edit-event/(:any)'] = "event/editEvent/$1";

$route['faq/list-faq'] = "faq/listFaq";
$route['faq/add-faq'] = "faq/addFaq";
$route['faq/edit-faq/(:any)'] = "faq/editFaq/$1";



/****************** Doctor Url **********************/
$route['doctor/doctor-profile-setup'] = "doctor/doctorProfile";
$route['doctor/doctor-document-verification'] = "doctor/doctorDocumentVerification";
$route['doctor/documents-upload-success'] = "doctor/uploadSuccess";
$route['blog/write-an-article'] = "blog/writeArticle";
$route['media/upload-media'] = "media/uploadMedia";
$route['forum/create-forum'] = "forum/createForum";
$route['forum/post-forum'] = "forum/postForum";
$route['webinar/create-webinar'] = "webinar/createWebinar";
$route['doctor/update-profile'] = "doctor/updateProfile";


/****************** Member Url **********************/
$route['member/member-profile-setup'] = "member/memberProfile";
$route['member/select-category'] = "member/selectCategory";
$route['member/followed-doctor'] = "member/followedDoctors";
$route['member/search-doctor'] = "member/searchDoctor";
$route['message/contact-doctor/(:any)'] = "message/contactDoctor/$1";
$route['message/view-message/(:any)'] = "message/viewMessage/$1";

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
