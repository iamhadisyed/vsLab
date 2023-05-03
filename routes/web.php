<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('landing-home');
});

Route::get('getgooglefile/1RXj0t8NMYt_Jr5lW-BIeAIxw4aX5VshV','DownloaderController@getfmdataset');
Route::get('getcounts/1RXj0t8NMYt_Jr5lW-BIeAIxw4aX5VshV','DownloaderController@getdownloadcounts');

Route::group(['middleware' => ['cors']], function () {
    Route::get('/login/cas', function () {
        return cas()->authenticate();
    });
    Route::get('/login/cas/{group}', function ($group='groups') {
        return cas()->authenticatere($group);
    });
});

Route::get('/cas/callback', 'Auth\CasController@callback')->name('cas.callback');

Route::get('/cas/enuser', 'Auth\CasController@enuser')->name('cas.enuser')->middleware('auth.basic');
Route::get('/cas/engroup', 'Auth\CasController@engroup')->name('cas.engroup')->middleware('auth.basic');
Route::get('/refreshstatus', 'SysAdminController@refreshInstances')->middleware('auth.basic');
Route::get('/cas/disgroup', 'Auth\CasController@disgroup')->name('cas.disgroup')->middleware('auth.basic');

Route::get('service-agreement', function () {
    return view('serviceagreement');
});
Route::get('privacy-statement', function () {
    return view('privacystatement');
});
//Route::get('landing', function ()    {
//    $data = [];
//    return view('adminlte::layouts.landing',$data);
//})->name('landing');

Route::get('pagenotfound', 'ErrorController@pagenotfound')->name('404');;
Route::get('serverunavailable', 'HomeController@serverunavailable');
Route::get('verifyemail/{token}', 'Auth\RegisterController@verify');
//Route::get('session-time-out', 'HomeController@session_timeout');

//Route::group(['middleware' => ['web', 'auth', 'check_profile', 'check_role', 'role:TA|instructor|super_user']], function () {
Route::group(['middleware' => ['web', 'auth', 'check_profile','role:TA|instructor|super_user']], function () {
    Route::get('usermanagement/all-users-table', 'UserController@getUsersTable');
    Route::get('usermanagement/all-users-json', 'UserController@getUsersJson');
});

Route::group(['middleware' => ['web', 'auth', 'check_profile','role:TA|instructor|super_user']], function () {
    Route::get('groups/members-table/{id}', 'GroupController@getMembersTable')->where('id', '[0-9]+');
    Route::get('groups/members-json/{id}', 'GroupController@getMembersJson')->where('id', '[0-9]+');
    Route::get('groups/available-roles', 'GroupController@getAvailableRoles');
    Route::post('groups/add-members', 'GroupController@addMembers');
    Route::post('groups/remove-members', 'GroupController@removeMembers');
    Route::post('groups/batch-enroll', 'GroupController@batchEnrollMembers');
    Route::post('groups/change-roles', 'GroupController@changeRoles');
    Route::post('groups/delete', 'GroupController@delete');
    //Route::resource('subgroups', 'SubGroupController', ['parameters' => ['subgroups' => 'group_id']]);
    Route::get('subgroups/{id}', 'SubGroupController@index')->where('id', '[0-9]+');;
    Route::post('subgroups/create', 'SubGroupController@store')->name('subgroups.create');
    Route::post('subgroups/update', 'SubGroupController@update');
    Route::post('subgroups/delete', 'SubGroupController@delete');
    Route::post('subgroups/updateMembers', 'SubGroupController@updateMembers');

    Route::resource('groups', 'GroupController');

    //Route::resource('grade', 'GradingController');
//    Route::get('grade', 'GradingController@index');
    Route::get('grade/{id}/{labid}','GradingController@show')->where('id', '[0-9]+')->where('labid', '[0-9]+');
//    Route::post('gradingpolicy','LabSubmissionController@gradingpolicy');
    Route::post('gradingpolicyforlab','LabSubmissionController@gradingpolicyforlab');
    Route::get('labsdeploy/labcontents-table/{id}', 'LabDeployController@getContentsTale')->where('id', '[0-9]+');


    Route::get('labsdeploy/labs-table/{id}', 'LabDeployController@getLabsTable');
    Route::get('labsdeploy/{id}', 'LabDeployController@index')->where('id', '[0-9]+');
    Route::post('labsdeploy/assign', 'LabDeployController@assignLabsToTeams')->name('labs.assign');
    Route::post('labsdeploy/assign-labcontent', 'LabDeployController@assignLabContent')->name('contents.assign');
    Route::post('labsdeploy/update', 'LabDeployController@update');
    Route::post('labsdeploy/updateduedate', 'LabDeployController@updateduedate');
    Route::post('labsdeploy/deploy', 'LabDeployController@deploy');
    Route::post('labenv/labstatus', 'LabEnvListController@labStatus');
    Route::post('labenv/deploy', 'LabEnvListController@deploy');
    Route::post('labenv/release', 'LabEnvListController@releaseResource');
    Route::post('labenv/delete', 'LabEnvListController@delete');
    Route::post('labenv/saveTemplate', 'LabEnvListController@saveTemp');
    Route::post('labenv/deleteTemplate', 'LabEnvListController@deleteTemp');

    Route::get('labenv/deployed-labs-table', 'LabEnvListController@getDeployedLabTable');

//    Route::get('labcontent', 'LabContentListController@index')->name('labcontent');
    Route::post('labsdeploy/labstatus', 'LabDeployController@labStatus');
    Route::post('labsdeploy/release', 'LabDeployController@releaseResource');
    Route::post('labsdeploy/reopentasks', 'LabDeployController@reopenTasks');
    Route::post('labsdeploy/delete', 'LabDeployController@delete');


//    Route::get('sites/get', 'GroupController@batchEnrollMembers');
    Route::post('labcontents/saveContent', 'LabContentController@saveContent');
    Route::post('labcontents/updateContent', 'LabContentController@updateContent');
    Route::post('labcontents/deleteContent', 'LabContentController@deleteContent');
    Route::post('labcontents/saveTask', 'LabContentController@saveTask');
    Route::post('labcontents/updateTask', 'LabContentController@updateTask');
    Route::post('labcontents/deleteTask', 'LabContentController@deleteTask');
    Route::get('labcontents', 'LabContentController@index');
    Route::get('labrepo/{flag}','LabContentController@repoindex')->where('flag', '[0-2]');
    Route::get('labcontents/tasks-table/{id}', 'LabContentController@getTasksTable')->where('id', '[0-9]+');
//    Route::get('labcontents/submissions-table/{id}', 'LabContentController@getSubmissionsTable')->where('id', '[0-9]+');

    Route::resource('labenvdesign', 'LabEnvDesignController');
    Route::get('cloud/getDesign/{tempId}', 'LabEnvDesignController@getTempDesign');
    Route::post('cloud/updateTemplate', 'LabEnvDesignController@updateTemp');
    Route::get('cloud/getImageList/', array('uses' => 'LabEnvDesignController@getResources'));

    Route::post('gradetask','LabSubmissionController@gradetask');

    Route::get('timeline/{id}', 'UserController@timelinewithid')->where('id', '[0-9]+');
});

Route::group(['middleware' => ['web', 'auth', 'role:site_admin']], function () {
    Route::get('mysites/mysite', 'SiteController@mySite');
    Route::get('mysites/groups/{id}', 'SiteController@siteGroups');
    Route::get('mysites/users/{id}', 'SiteController@siteUsers');
    Route::get('mysites/available-roles', 'SiteController@getAvailableRoles');
    Route::get('mysites/all-users-table', 'UserController@getUsersTable');
    Route::post('mysites/setting', 'SiteController@mySiteSetting');
    Route::post('mysites/resource-allocation', 'SiteController@allocateResource');
    Route::post('mysites/add-users', 'SiteController@addUsers');
    Route::post('mysites/batch-enroll', 'SiteController@batchEnrollUsers');
    Route::post('mysites/change-roles', 'SiteController@changeRoles');
    Route::post('mysites/remove-users', 'SiteController@removeUsers');
});

Route::group(['middleware' => ['web', 'auth', 'role:system_admin']], function () {
    Route::post('usermanagement/delete', 'UserController@destroy');
    Route::post('sites/update', 'SiteController@update');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('sites', 'SiteController');
    Route::resource('usermanagement', 'UserController');
    //Route::resource('profiles', 'UserProfileController');
    Route::get('sysadmin/cloud-config','SysAdminController@getCloudConfig');
    Route::get('sysadmin/instances','SysAdminController@getInstances');
    Route::post('sysadmin/instances/autosuspend', 'SysAdminController@setautosuspend')->name('instances.autosuspend');
    Route::get('sysadmin/instances/{id}', 'SysAdminController@getInstancesforId')->where('id', '[0-9]+');
    Route::get('sysadmin/instances/{id}/table', 'SysAdminController@getInstanceswithId')->where('id', '[0-9]+');
    Route::get('/oauth-settings', 'OAuthSettingsController@index')->name('oauth-settings');
});

Route::group(['middleware' => ['web', 'auth']], function () {
//    Route::get('labenv1/{id}', 'LabEnvListController@show1');
    Route::get('grade/{id}/{labid}','GradingController@show')->where('id', '[0-9]+')->where('labid', '[0-9]+');
    Route::get('grade/{id}/{labid}/all','GradingController@showall')->where('id', '[0-9]+')->where('labid', '[0-9]+');
    Route::get('getlabcontentbyid', 'LabContentController@getlabcontentbyid');
    Route::get('getlabtaskbyid', 'LabContentController@getlabtaskbyid');
    Route::get('getlabtaskbylab', 'LabContentController@getlabtaskbylab');
    Route::get('getlabinfobyid', 'LabContentController@getlabinfobyid');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );
    Route::get('cloud/getNetworkTopology/{project}','CloudResource@getNetworkTopology');
    Route::get('session-timeout', 'SessionController@timeout');
    Route::post('client-closed', 'SessionController@clientClosed');

    Route::resource('profiles', 'UserProfileController');

    Route::group(['middleware' => ['web', 'auth','check_profile']], function () {
        Route::get('mylabs/{id}', 'MyLabController@refreshtable')->where('id', '[0-9]+');
        Route::get('content/CNS-20002', 'LabEnvController@cns20002');
        Route::get('content/CNS-20001', 'LabEnvController@cns20001');
        Route::get('content/CNS-10002', 'LabEnvController@cns10002');
        Route::get('content/CNS-10001', 'LabEnvController@cns10001');
        Route::get('content/CNS-10003', 'LabEnvController@cns10003');
        Route::get('content/CNS-00003', 'LabEnvController@cns00003');
        Route::get('content/CNS-00001', 'LabEnvController@cns00001');
        Route::get('content/SYS-00008', 'LabEnvController@sys00008');
        Route::get('content/SYS-00009', 'LabEnvController@sys00009');
        Route::get('content/SYS-00009', 'LabEnvController@sys00009');
        Route::get('content/conceptmap', 'LabEnvController@conceptmap');
        Route::get('mylabs/{id}/show', 'MyLabController@index')->where('id', '[0-9]+');
        Route::get('submission/{userid}/{labid}', 'MyLabController@submission')->where('userid', '[0-9]+')->where('labid', '[0-9]+');
        Route::get('labsubmission/{username}/{labid}', 'MyLabController@getsubmission')->where('labid', '[0-9]+');
        Route::get('labsubmission/{labid}', 'MyLabController@getsubmission1')->where('labid', '[0-9]+');
        //        Route::resource('class', 'ClassController');
//        Route::resource('labcontent', 'LabContentListController');
//        Route::resource('mylab/{id}', 'MyLabController');



//        Route::resource('editor', 'EditorController');

//        Route::resource('lab.task.env', 'LabEnvController');
//        Route::resource('lab.task', 'LabTaskController');
        Route::resource('submit', 'LabSubmissionController');
//        Route::resource('lab', 'LabController', ['parameters' => [
//            'lab' => 'id'
//        ]]);

        Route::post('deletesubmission','LabSubmissionController@destroy');
        Route::post('groupsubmit','LabSubmissionController@groupsubmit');
        Route::post('groupsubmit/updatetext','LabSubmissionController@updatetext');
        Route::post('feedbacksubmit','LabSubmissionController@feedbacksubmit');
        Route::post('uploadfile','LabSubmissionController@uploadfile');
        Route::post('uploadfileforlab','LabSubmissionController@uploadfileforlab');
        Route::post('deletefileforlab','LabSubmissionController@deletefileforlab');
        Route::post('uploadpdfforlab','LabSubmissionController@uploadpdfforlab');
        Route::post('deletepdfforlab','LabSubmissionController@deletepdfforlab');
        Route::post('groupuploadfile','LabSubmissionController@groupuploadfile');
//        Route::post('uploadsurvey','LabSubmissionController@uploadsurvey');
        Route::post('updatesubmission','LabSubmissionController@updatesubmission');
        Route::get('tasksubmission', 'LabSubmissionController@getsubmissionbytask');
        Route::get('tasksubmissionbysubgroup', 'LabSubmissionController@getsubmissionbytaskandsubgroup');
        Route::get('tasksubmissionbyuser', 'LabSubmissionController@getsubmissionbytaskanduser');
        Route::get('getfilebylab', 'LabSubmissionController@getfilebylab');

        Route::get('checktaskfinished', 'LabSubmissionController@checktaskfinished');
        Route::post('finishtask','LabSubmissionController@finishtask');

        Route::get('getlabcontentbylabenv','LabSubmissionController@getlabcontentbylabenv');
        Route::get('getlabcontentbylabid','LabSubmissionController@getlabcontentbylabid');
        Route::get('getgrade','LabSubmissionController@getgrade');
        Route::get('getgradenouserid','LabSubmissionController@getgradenouserid');

        Route::get('gettaskfullgrade','LabSubmissionController@gettaskfullgrade');
        Route::get('getlabgradingpolicy','LabSubmissionController@getlabgradingpolicy');
        Route::get('getlabgradingpolicyforlab','LabSubmissionController@getlabgradingpolicyforlab');
        Route::get('checktaskgraded', 'LabSubmissionController@checktaskgraded');
        Route::get('labenv/{id}', 'LabEnvListController@show2')->where('id', '[0-9]+');
        Route::get('labenvnew/{id}', 'LabEnvListController@show3')->where('id', '[0-9]+');
        Route::get('labenv', 'LabEnvListController@index');
        Route::get('timeline', 'UserController@timeline');
        Route::post('geteventlog', 'UserController@gettimelinebyuser');

//        Route::resource('labenv', 'LabEnvListController');
//        Route::get('profiles', 'UserProfileController@show');
    });
    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
});

