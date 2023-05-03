<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|


Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/

//Route::get('/', array('as' => 'home', 'uses' => 'HomeController@home'));
//Route::get('/landing', array('as' => 'landing', 'uses' => 'HomeController@landing'));
Route::get('/service-agreement', function () {
   include public_path().'/service-agreement.php';
});



//Route::get('/user/{username}', array('as' => 'profile-user', 'uses' => 'ProfileController@user'));

/*
| Authenticated group
*/
//Route::group(array('middleware' => 'auth'), function() {
//
//    /*
//    | CSRF protection group
//    */
//    Route::group(array('middleware' => 'csrf'), function() {
//        /*
//        | Change password (POST)
//        */
//        Route::post('/account/change-password', array(
//            'as' => 'account-change-password-post',
//            'uses' => 'AccountController@postChangePassword'
//        ));
//    });
//
//    /*
//    | Change password (GET)
//    */
//    Route::get('/account/change-password', array(
//        'as' => 'account-change-password',
//        'uses' => 'AccountController@getChangePassword'
//    ));
//
//    /*
//    | Sign out (GET)
//    */
//    Route::get('/account/signout', array(
//        'as' => 'account-signout',
//        'uses' => 'AccountController@getSignOut'
//    ));
//});

/*
| Unauthenticated group
*/
//Route::group(array('middleware' => 'guest'), function() {
//
//    /*
//    | CSRF protection group
//    */
//    Route::group(array('middleware' => 'csrf'), function() {
//        /*
//        | Create account (POST)
//        */
//        Route::post('/account/register', array(
//            'as' => 'account-register-post',
//            'uses' => 'AccountController@postCreate'
//        ));
//
//        /*
//        | Sign in (POST)
//        */
//        Route::post('/account/signin', array(
//            'as' => 'account-signin-post',
//            'uses' => 'AccountController@postSignIn'
//        ));
//
//        /*
//        | Forgot password (POST)
//        */
//        Route::post('/account/forgot-password', array(
//            'as' => 'account-forgot-password-post',
//            'uses' => 'AccountController@postForgotPassword'
//        ));
//    });
//
//    /*
//    | Forgot password (GET)
//    */
//    Route::get('/account/forgot-password', array(
//        'as' => 'account-forgot-password',
//        'uses' => 'AccountController@getForgotPassword'
//    ));
//
//    Route::get('/account/recover/{code}', array(
//        'as' => 'account-recover',
//        'uses' => 'AccountController@getRecover'
//    ));
//
//    /*
//    | Sign in (GET)
//    */
//    Route::get('/account/signin', array(
//        'as' => 'account-signin',
//        'uses' => 'AccountController@getSignIn'
//    ));
//
//    /*
//    | Create account (GET)
//    */
//    Route::get('/account/register', array(
//        'as' => 'account-register',
//        'uses' => 'AccountController@getCreate'
//    ));
//
//    Route::get('/account/activate/{code}', array(
//        'as' => 'account-activate',
//        'uses' => 'AccountController@getActivate'
//    ));
//
//});

//Route::filter('csrf', function() {
//    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
//    if (Session::token() != $token)
//        throw new Illuminate\Session\TokenMismatchException;
//});

//Route::group(['middleware' => ['web']], function () {
//Route::get('projects', array('as' => 'projects', 'uses' => 'ProjectController@getResource'));
//Route::get('blog', function() { return View::make('blog'); });
//Route::get('news', function() { return View::make('news'); });
//Route::get('blog-post', function() { return View::make('blog-post'); });
//Route::get('experiment', function() { return View::make('experiment'); });

    //Auth::routes();

    Route::get('community', function () {
        return view::make('community');
    });
    Route::get('system-status', function () {
        return view::make('system-status');
    });
    Route::get('community', function () {
        return view::make('community');
    });
    Route::get('mobile', function () {
        return view::make('mobile');
    });
    Route::get('monitor', function () {
        return view::make('monitor');
    });

    /**
     *  WorkSpace Functions
     */

    Route::get('myworkspace', array('as' => 'myworkspace', 'uses' => 'WorkspaceController@getWorkspace'));
    Route::get('experiment', array('as' => 'experiment', 'uses' => 'ExperimentController@getResource'));
    Route::get('cloud/getWallPaper', array('uses' => 'WorkspaceController@getWallPaper'));
    Route::post('cloud/acceptFile', array('uses' => 'WorkspaceController@acceptFile'));
    Route::post('cloud/setWallPaper', array('uses' => 'WorkspaceController@setWallPaper'));
    Route::post('cloud/upload', array('uses' => 'WorkspaceController@uploadImage'));
    Route::get('cloud/upload', array('uses' => 'WorkspaceController@uploadImage'));
    Route::post('cloud/getOwncloudFilelist', array('uses' => 'OwnCloudController@getOwncloudFilelist'));
    Route::post('cloud/getHelpUrl', array('uses' => 'WorkspaceController@getHelpUrl'));
    Route::get('cloud/getActivityLog', array('uses' => 'WorkspaceController@getActivityLog'));
    Route::post('workspace/keepAlive', array('as' => 'keepAlive', 'uses' => 'WorkspaceController@keepAlive'));
//    Route::get('cloud/redirect/{url}', array('uses' => 'MyRedirect@redirect'));

    /**
     *  Cloud Resource Management Functions (admin)
     */
    Route::get('cloud/getAllUserList', array('uses' => 'OpenStackResource@getAllUserList'));
    Route::get('cloud/getProjectList', array('uses' => 'OpenStackResource@getProjectList'));
    Route::get('cloud/getProjectUserList/{projectId}', array('uses' => 'OpenStackResource@getProjectUserList'));
    Route::post('cloud/createProject', array('as' => 'create-project', 'uses' => 'CloudV2Resource@createProjectV2'));
    Route::post('cloud/updateProject', array('as' => 'update-project', 'uses' => 'OpenStackResource@updateProject'));
    Route::post('cloud/deleteProject', array('as' => 'delete-project', 'uses' => 'OpenStackResource@deleteProject'));
    Route::get('cloud/getDomain/{domainName}', array('uses' => 'OpenStackResource@getDomain'));
    Route::get('cloud/getQuota/{projectId}', array('uses' => 'OpenStackResource@getQuota'));

    Route::get('cloud/getProjectMembers/{tenantId}', array('uses' => 'CloudResource@getProjectMembers'));
    Route::post('cloud/setProjectMember', array('uses' => 'CloudResource@setProjectMember'));
    Route::post('cloud/setProjectMemberBySubgroup', array('uses' => 'CloudResource@setProjectMemberBySubgroup'));
    Route::get('cloud/getServers/{project}', array('uses' => 'CloudResource@getServerList'));

    Route::get('cloud/getResources/{project}', array('uses' => 'CloudResource@getResources'));
    Route::get('cloud/getImageList/{project}', array('uses' => 'CloudResource@getResources'));
    //Route::get('cloud/getImages/{tenantName}', array('uses' => 'CloudResource@getImages'));
    Route::get('cloud/getImage/{project}/{imageId}', array('uses' => 'CloudResource@getImage'));
    //Route::get('cloud/getFlavors/{tenantName}', array('uses' => 'CloudResource@getFlavors'));
    Route::get('cloud/getNetworks/{project}', array('uses' => 'CloudResource@getNetworkList'));
    Route::get('cloud/getHypervisors', array('uses' => 'OpenStackResource@getHypervisors'));
//    Route::get('cloud/getNetworkTopology/{project}', array('uses' => 'CloudResource@getNetworkTopology'));


    /**
     *  Cloud Resource Management Functions (user)
     */
    Route::get('cloud/getVM/{project}/{serverId}', array('uses' => 'CloudResource@getVM'));
    Route::get('cloud/getConsole/{project}/{serverId}', array('uses' => 'CloudResource@getConsole'));
    Route::post('cloud/createVM', array('as' => 'create-vm', 'uses' => 'CloudResource@createVM'));
    Route::post('cloud/createNetwork', array('as' => 'create-network', 'uses' => 'CloudResource@createNetwork'));
    Route::post('cloud/vmAction', array('uses' => 'CloudResource@vmAction'));
    Route::post('cloud/rebootVM', array('as' => 'reboot-vm', 'uses' => 'CloudResource@rebootVM'));
    Route::post('cloud/deleteVM', array('as' => 'delete-vm', 'uses' => 'CloudResource@deleteVM'));
    Route::post('workspace/fileUpload', array('as' => 'fileUpload', 'uses' => 'UploadController@fileUpload'));

    /**
     *  System Management Functions
     */
    Route::get('sysadmin/getSystemConfigData', array('uses' => 'SysAdminController@getSystemConfigData'));
    Route::post('sysadmin/postSystemConfigData', array('uses' => 'SysAdminController@postSystemConfigData'));
    Route::get('sysadmin/getPermissionList', array('uses' => 'PermissionController@getList'));
    Route::post('sysadmin/addPermission', array('uses' => 'PermissionController@store'));
    Route::post('sysadmin/updatePermission/{id}', array('uses' => 'PermissionController@update'));
    Route::get('sysadmin/getRoleList', array('uses' => 'RoleController@getList'));
    Route::post('sysadmin/addRole', array('uses' => 'RoleController@store'));
    Route::post('sysadmin/updateRole/{id}', array('uses' => 'RoleController@update'));

    /**
     *  Site Management Functions
     */
    Route::get('siteadmin/getSiteAll', array('uses' => 'SiteController@getSiteAll'));
    Route::post('siteadmin/addSite', array('uses' => 'SiteController@store'));
    Route::post('siteadmin/updateSite/{id}', array('uses' => 'SiteController@update'));
    Route::get('siteadmin/getSiteList', array('uses' => 'SiteController@getSiteList'));
    Route::post('siteadmin/groupApplicationProcess', array('uses' => 'SiteController@applicationProcess'));


    Route::post('group/addNewthothSite', array('uses' => 'GroupController@addNewthothSite'));
    Route::post('group/addGroup2Site', array('uses' => 'GroupController@addGroup2Site'));
    Route::get('group/getroleresTable', array('uses' => 'GroupController@getroleresTable'));

    Route::get('group/getGlobalAdmin', array('uses' => 'GroupController@getGlobalAdmin'));

    /**
     *  User Management Functions
     */
    Route::get('useradmin/getUserList', array('uses' => 'UserController@getList'));
    Route::post('useradmin/getUserList', array('uses' => 'UserController@getList'));
    Route::get('useradmin/getUserProfile/{id}', array('uses' => 'UserProfileController@show'));
    Route::post('useradmin/updateUserRole/{id}', array('uses' => 'UserController@updateRole'));
    Route::get('useradmin/getUserGroups/{id}', array('uses' => 'UserController@getGroups'));
    Route::get('useradmin/getUserProfileRoleGroups/{id}', array('uses' => 'UserController@getProfileRoleGroups'));
    Route::post('useradmin/changePassword/{id}', array('uses' => 'UserAdminController@changePassword'));
    Route::post('useradmin/addNewUser', array('uses' => 'UserAdminController@addNewUser'));

    Route::post('useradmin/searchUser', array('uses' => 'UserAdminController@searchUser'));
    Route::post('useradmin/searchUserRole', array('uses' => 'UserAdminController@searchUserRole'));
    Route::post('useradmin/searchUserRolebyRole', array('uses' => 'UserAdminController@searchUserRolebyRole'));
    Route::get('useradmin/getProfile', array('uses' => 'UserAdminController@selectLdapUser'));
    Route::get('useradmin/checkIfInvited', array('uses' => 'UserAdminController@checkIfInvited'));
    Route::get('useradmin/checkIfShowHelp', array('uses' => 'UserAdminController@checkIfShowHelp'));
    Route::post('useradmin/updateUserProfile', array('uses' => 'UserAdminController@updateUserProfile'));
    Route::post('useradmin/userRoleUpdate', array('uses' => 'UserAdminController@userRoleUpdate'));
    Route::get('useradminp/getsentPendingEnroll2', array('uses' => 'UserAdminController@getsentPendingEnroll2'));
    Route::get('useradmin/downloadvpnconifg', array('uses' => 'UserAdminController@downloadvpnconifg'));
    Route::get('useradmin/getFirstLogin', array('uses' => 'UserAdminController@getFirstLogin'));
    Route::get('useradmin/getShowHelp', array('uses' => 'UserAdminController@getShowHelp'));


    /**
     *  Group Management Functions
     */
    Route::get('group/getGroupsBySite/{id}', array('uses' => 'GroupController@getListBySite'));
    Route::post('group/addNewGroup', array('uses' => 'GroupController@store'));
    Route::get('group/getGroupsByOwner', array('uses' => 'GroupController@getListByOwner'));
    Route::get('group/getGroupUser/{id}', array('uses' => 'GroupController@getGroupUserById'));
    Route::get('group/getGroupInfo/{id}', array('uses' => 'GroupController@getGroupInfo'));
    Route::post('group/updateGroup', array('uses' => 'GroupController@update'));
    Route::post('group/deleteGroup', array('uses' => 'GroupController@delete'));
    Route::get('group/getGroupAvailableRoles', array('uses' => 'GroupController@getGroupAvailableRoles'));

    //Route::post('cloud/updateUserProfile', array('uses' => 'GroupController@updateUserProfile'));
    Route::post('group/searchGroup', array('uses' => 'GroupController@searchGroup'));
    Route::post('group/enrollGroup', array('uses' => 'GroupController@enrollGroup'));
    Route::post('group/getPendingEnroll', array('uses' => 'GroupController@getPendingEnroll'));
    Route::post('group/getPendingEnrol2', array('uses' => 'GroupController@getPendingEnrol2'));
    Route::post('group/getSuperuserPendingEnroll', array('uses' => 'GroupController@getSuperuserPendingEnroll'));
    Route::post('group/getGroupPendingEnroll', array('uses' => 'GroupController@getGroupPendingEnroll'));
    Route::post('group/approveEnrollGroup', array('uses' => 'GroupController@approveEnrollGroup'));
    Route::get('group/getOwnGroupList', array('uses' => 'GroupController@getOwnGroupList'));
    Route::get('group/getGroupResourceTable', array('uses' => 'GroupController@getGroupResourceTable'));
    Route::get('group/getOwnGroupList_byRole', array('uses' => 'GroupController@getOwnGroupList_byRole'));
    Route::get('group/getOwnGroupList_array', array('uses' => 'GroupController@getOwnGroupList_array'));
    Route::post('group/groupBasedEnroll', array('uses' => 'GroupController@groupBasedEnroll'));
    Route::post('group/inviteGroup', array('uses' => 'GroupController@inviteGroup'));
    Route::get('group/getPendingInvite', array('uses' => 'GroupController@getPendingInvite'));
    Route::get('group/getMemberAll', array('uses' => 'GroupController@getMemberAll'));
    Route::post('group/joinInviteGroup', array('uses' => 'GroupController@joinInviteGroup'));
    Route::post('group/updateRoleJson', array('uses' => 'GroupController@updateRoleJson'));
    Route::post('group/createGroup', array('uses' => 'GroupController@createGroup'));
    Route::post('group/createClass', array('uses' => 'GroupController@createClass'));
    Route::post('group/leaveGroup', array('uses' => 'GroupController@leaveGroup'));
    Route::post('group/getGroupMembers', array('uses' => 'GroupController@getGroupMembers'));
    Route::get('group/getPendingClass', array('uses' => 'GroupController@getPendingClass'));
    Route::post('group/AdminApproveClass', array('uses' => 'GroupController@AdminApproveClass'));
    Route::post('group/AdminRejectClass', array('uses' => 'GroupController@AdminRejectClass'));

    Route::get('cloud/hearbeat', array('uses' => 'WorkspaceController@hearbeat'));
    Route::get('cloud/queryHearbeat', array('uses' => 'WorkspaceController@queryHearbeat'));
    Route::get('cloud/queryHearbeatBatch', array('uses' => 'WorkspaceController@queryHearbeatBatch'));
    Route::post('cloud/delete_group1', array('uses' => 'WorkspaceController@delete_group1'));

    Route::get('cloud/get_all_group_member_tree', array('uses' => 'WorkspaceController@get_all_group_member_tree'));
    Route::get('group/getGroupMembers4subgroup', array('uses' => 'GroupController@getGroupMembers4subgroup'));

    Route::get('cloud/getTemplate1', array('uses' => 'WorkspaceController@getTemplate'));
    Route::get('cloud/getPermissions', array('uses' => 'WorkspaceController@getPermissions'));
    Route::post('cloud/PPpermissionUpdate', array('uses' => 'WorkspaceController@PPpermissionUpdate'));
    Route::post('cloud/doNotshowhelp', array('uses' => 'WorkspaceController@doNotshowhelp'));

    Route::post('group/CancelSent', array('uses' => 'GroupController@CancelSent'));

    /**
     *  Subgroup Management Functions
     */
    Route::post('subgroup/delete_subgroup', array('uses' => 'SubGroupController@delete_subgroup'));
    Route::post('subgroup/create_subgroup', array('uses' => 'SubGroupController@create_subgroup'));
    Route::post('subgroup/postGroup4SubGroupNew', array('uses' => 'SubGroupController@postGroup4SubGroupNew'));
    Route::post('subgroup/updateSubGroup', array('uses' => 'SubGroupController@updateSubGroup'));
    Route::post('subgroup/update_subgroup_member', array('uses' => 'SubGroupController@update_subgroup_member'));
    Route::get('subgroup/getSubgroupTemplateProject/{group_name}', array('uses' => 'SubGroupController@getSubgroupTemplateProject'));
    Route::get('subgroup/getTeamList/{group_name}', array('uses' => 'SubGroupController@getTeamList'));
    Route::post('subgroup/rename_subgroup', array('uses' => 'SubGroupController@rename_subgroup'));
    Route::post('subgroup/create_individual_team', array('uses' => 'SubGroupController@create_individual_team'));

    /**
     *  Lab Management Functions
     */
    Route::post('labs/update_lab_info', array('uses' => 'LabsController@update_lab_info'));
    Route::post('labs/assignTemplateButton', array('uses' => 'LabsController@assignTemplateButton'));
    Route::post('labs/assignTemplateToTeams', array('uses' => 'LabsController@assignTemplateToTeams'));
    Route::post('labs/deploy_lab', array('uses' => 'LabsController@deploy_lab'));
    Route::post('labs/pre_deploy_labs', array('uses' => 'WorkspaceController@pre_deploy_labs'));
    //Route::post('cloud/delete_labs', array('uses' => 'WorkspaceController@delete_labs'));
    Route::post('labs/delete_lab', array('uses' => 'LabsController@delete_lab'));
    Route::post('labs/delete_stack', array('uses' => 'LabsController@delete_stack'));
    Route::post('labs/delete_project', array('uses' => 'LabsController@delete_project'));
    Route::post('labs/checkStackStatus', array('uses' => 'CloudV2Resource@checkStackStatusV2'));
    Route::post('labs/checkStackEvents', array('uses' => 'CloudV2Resource@checkStackEventsV2'));
    Route::post('labs/update_Lab_Project', array('uses' => 'WorkspaceController@update_Lab_Project'));
    Route::get('labs/getOpenClassLabTemp', array('uses' => 'LabsController@getOpenClassLabTemp'));
    Route::get('labs/getOwnClassLabTemp', array('uses' => 'LabsController@getOwnClassLabTemp'));
    Route::get('labs/getWorkingLabList', array('uses' => 'LabsController@getWorkingLabList'));

    /**
     *  Lab Design Functions
     */
    Route::get('cloud/getTestingLab', array('uses' => 'HeatController@getTestingLab'));
    Route::post('cloud/assignTemplate', array('uses' => 'HeatController@assignTemp'));
    Route::post('cloud/saveTemplate', array('uses' => 'HeatController@saveTemp'));
 //   Route::post('cloud/updateTemplate', array('uses' => 'HeatController@updateTemp'));
    Route::post('cloud/updateAssignTemp', array('uses' => 'HeatController@updateAssignTemp'));
    Route::post('cloud/searchAllUser', array('uses' => 'HeatController@searchAllUser'));
    Route::get('cloud/getTemplate', array('uses' => 'HeatController@getTemp'));
 //   Route::get('cloud/getDesign/{tempId}', array('uses' => 'HeatController@getTempDesign'));
    Route::get('cloud/getOpenTempId/{labId}', array('uses' => 'HeatController@getOpenTempId'));
    Route::post('cloud/shareTemp', array('uses' => 'HeatController@shareTemp'));
    Route::post('cloud/copyTemp', array('uses' => 'HeatController@copyTemp'));
    Route::post('cloud/deleteTemp', array('uses' => 'HeatController@deleteTemp'));
    Route::post('cloud/deleteAssignTemp', array('uses' => 'HeatController@deleteAssignTemp'));
    Route::post('cloud/verifyHeatTemp', array('uses' => 'CloudResource@verifyHeatTemplate'));
    Route::post('cloud/deployTemp', array('uses' => 'CloudV2Resource@createStackV2'));
    Route::post('cloud/deleteProject', array('uses' => 'CloudV2Resource@deleteStackV2'));
    Route::get('cloud/getSharedMembers/{tempId}', array('uses' => 'HeatController@getSharedMembers'));
    Route::get('cloud/getTree', array('uses' => 'LabTreeController@getTreeList'));
    Route::get('cloud/getTreeContent/{course_id}/{name_id}', array('uses' => 'LabTreeController@getTreeContent'));
    Route::get('cloud/getOpenTreeContent/{course_id}/{name_id}', array('uses' => 'LabTreeController@getOpenTreeContent'));
    Route::get('cloud/getLeaf/{course_id}/{name_id}', array('uses' => 'LabTreeController@getContent'));
    Route::get('cloud/getOwnClass', array('uses' => 'HeatController@getOwnClass'));
    Route::get('cloud/getLabDesign', array('uses' => 'HeatController@getLabDesign'));
    Route::get('cloud/getOpenLabs', array('uses' => 'LabsController@getOpenLabs'));

    /**
     *  ELK Stack Functions
     */
    Route::post('elasticsearch/indexDocument', array('uses' => 'ElasticsearchResource@indexDocument'));
    Route::get('elasticsearch/searchDocuments', array('uses' => 'ElasticsearchResource@searchDocuments'));
    Route::get('elasticsearch/getIndices', array('uses' => 'ElasticsearchResource@getIndices'));
    Route::get('elasticsearch/getDocumentTypes/{index}', array('uses' => 'ElasticsearchResource@getDocumentTypes'));
    Route::get('elasticsearch/getDocumentFields/{index}/{documentType}', array('uses' => 'ElasticsearchResource@getDocumentFields'));
    Route::get('elasticsearch/search', array('uses' => 'ElasticsearchResource@search'));
//});

Route::get('reports', function() { return View::make('vlabreports'); });
Route::get('about', function() { return View::make('about'); });
Route::get('openlabs', function() { return View::make('openlabs'); });
Route::get('projects', function() { return View::make('projects'); });
Route::get('useragreement', function() { return View::make('useragreement'); });

Html::macro('smart_tag', function($route, $text) {
    if( Request::path() == $route ) {
        $active = "class = 'active'";
    }
    else {
        $active = '';
    }

    return '<li ' . $active . '>' . link_to($route, $text) . '</li>';
});