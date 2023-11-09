<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthCompanyController;
use App\Http\Controllers\AuthEmployerController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyAccountController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CompanyReportController;
use App\Http\Controllers\CompanyVerificationController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\EmployerAccountController;
use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobReportController;
use App\Http\Controllers\JobSkillController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostReportController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\TimeTableController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\UserAchievementController;
use App\Http\Controllers\UserEducationController;
use App\Http\Controllers\UserExperienceController;
use App\Http\Controllers\UserHistoryController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserSkillController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//------------------------------------ADMIN-----------------------------------
// Only admin
Route::middleware(['auth:sanctum', 'abilities:*'])->controller(AdminController::class)
    ->prefix('admin')->group(function () {
        Route::get('/', 'getAdmin');

        Route::post('/mod', 'createModAccount');

        Route::put('/mod/lock/{id}', 'lockModAccount');
        Route::put('/mod/unlock/{id}', 'unlockModAccount');
        Route::put('/mod/ban/{id}', 'banModAccount');
        Route::put('/mod/unban/{id}', 'unbanModAccount');

        Route::put('/password', 'updatePassword');

        Route::delete('/mod/{id}', 'deleteModAccount');
    });

Route::middleware(['auth:sanctum', 'abilities:mod'])->controller(AdminController::class)
    ->prefix('mods')->group(function () {
        Route::get('/{id}', 'getModById');
        Route::get('/', 'getAllMods');

        Route::put('/{id}', 'updateModAccount');
    });

//------------------------------------USER------------------------------------

// --------User Account
// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(UserAccountController::class)
    ->prefix('user')->group(function () {
        Route::put('/password', 'updatePassword');
    });

// Only mod
Route::middleware(['auth:sanctum', 'abilities:mod'])->controller(UserAccountController::class)
    ->prefix('user-accounts')->group(function () {
        Route::get('/', 'getAllUserAccounts');

        Route::put('/ban/{id}', 'banUserAccount');
        Route::put('/unban/{id}', 'unbanUserAccount');
        Route::put('/lock/{id}', 'lockUserAccount');
        Route::put('/unlock/{id}', 'unlockUserAccount');

        Route::delete('/{id}', 'deleteUserAccount');
    });

// User and mod
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(UserAccountController::class)
    ->prefix('user-accounts')->group(function () {
        Route::get('/{id}', 'getUserAccountById');
    });

// ---------User Profile
// All roles
Route::middleware(['auth:sanctum'])->controller(UserProfileController::class)
    ->prefix('user-profiles')->group(function () {
        Route::get('/{id}', 'getUserProfile');
        Route::get('/', 'getAllUserProfiles');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(UserProfileController::class)
    ->prefix('user-profiles')->group(function () {
        Route::post('/avatar/{id}', 'updateUserAvatar');
        Route::put('/import/{id}', 'importUserProfile');
        Route::put('/', 'updateUserProfile');

        Route::put('/noti/mark-as-read/{id}', 'markNotificationAsRead');
        Route::put('/noti/mark-all-as-read', 'markAllNotificationsAsRead');
        Route::get('/noti/all', 'getUserNotifications');
        Route::get('/noti/unread', 'getUserUnreadNotifications');
    });

// Only company and employer
Route::middleware(['auth:sanctum', 'ability:company,employer'])->controller(UserProfileController::class)
    ->prefix('user-profiles')->group(function () {
        Route::post('/noti/job-invite', 'sendJobInvitationNotification');
    });

// ----------User Achievement
// All roles
Route::middleware(['auth:sanctum'])->controller(UserAchievementController::class)
    ->prefix('user-achievements')->group(function () {
        Route::get('/user/{user_id}', 'getUserAchievementsByUserId');
        Route::get('/{id}', 'getUserAchievementById');
        Route::get('/', 'getAllUserAchievements');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(UserAchievementController::class)
    ->prefix('user-achievements')->group(function () {
        Route::post('/', 'createUserAchievement');

        Route::put('/{id}', 'updateUserAchievement');
    });

// User and mod
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(UserAchievementController::class)
    ->prefix('user-achievements')->group(function () {
        Route::delete('/{id}', 'deleteUserAchievement');
    });

// ----------User Education
// All roles
Route::middleware(['auth:sanctum'])->controller(UserEducationController::class)
    ->prefix('user-educations')->group(function () {
        Route::get('/user/{user_id}', 'getUserEducationsByUserId');
        Route::get('/{id}', 'getUserEducationById');
        Route::get('/', 'getAllUserEducations');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(UserEducationController::class)
    ->prefix('user-educations')->group(function () {
        Route::post('/', 'createUserEducation');

        Route::put('/{id}', 'updateUserEducation');
    });

// User and mod
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(UserEducationController::class)
    ->prefix('user-educations')->group(function () {
        Route::delete('/{id}', 'deleteUserEducation');
    });

// ----------User Experience
// All roles
Route::middleware(['auth:sanctum'])->controller(UserExperienceController::class)
    ->prefix('user-experiences')->group(function () {
        Route::get('/user/{user_id}', 'getUserExperiencesByUserId');
        Route::get('/{id}', 'getUserExperienceById');
        Route::get('/', 'getAllUserExperiences');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(UserExperienceController::class)
    ->prefix('user-experiences')->group(function () {
        Route::post('/', 'createUserExperience');

        Route::put('/{id}', 'updateUserExperience');
    });

// User and mod
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(UserExperienceController::class)
    ->prefix('user-experiences')->group(function () {
        Route::delete('/{id}', 'deleteUserExperience');
    });

// ----------User Skill
// All roles
Route::middleware(['auth:sanctum'])->controller(UserSkillController::class)
    ->prefix('user-skills')->group(function () {
        Route::get('/user/{user_id}', 'getUserSkillsByUserId');
        Route::get('/{id}', 'getUserSkillById');
        Route::get('/', 'getAllUserSkills');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(UserSkillController::class)
    ->prefix('user-skills')->group(function () {
        Route::post('/', 'createUserSkill');

        Route::put('/{id}', 'updateUserSkill');
    });

// User and mod
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(UserSkillController::class)
    ->prefix('user-skills')->group(function () {
        Route::delete('/{id}', 'deleteUserSkill');
    });

// -----------User History
// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(UserHistoryController::class)
    ->prefix('user-histories')->group(function () {
        Route::post('/', 'createUserHistory');

        Route::put('/{id}', 'updateUserHistory');
    });

// Only moderator
Route::middleware(['auth:sanctum', 'abilities:mod'])->controller(UserHistoryController::class)
    ->prefix('user-histories')->group(function () {
        Route::get('/{id}', 'getUserHistoryById');
        Route::get('/', 'getUserHistories');

        Route::delete('/{id}', 'deleteUserHistory');
    });

//------------------------------------POST------------------------------------
// ----------Post
// All roles
Route::middleware(['auth:sanctum'])->controller(PostController::class)
    ->prefix('posts')->group(function () {
        Route::get('/user/{user_id}', 'getPostsByUserId');
        Route::get('/{id}', 'getPostById');
        Route::get('/', 'getAllPosts');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(PostController::class)
    ->prefix('posts')->group(function () {
        Route::post('/', 'createPost');

        Route::put('/{id}', 'updatePost');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(PostController::class)
    ->prefix('posts')->group(function () {
        Route::delete('/{id}', 'deletePost');
    });

// ----------Post Report
// All roles
Route::middleware(['auth:sanctum'])->controller(PostReportController::class)
    ->prefix('post-reports')->group(function () {
        Route::get('/{id}', 'getPostReportById');
        Route::get('/', 'getPostReports');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(PostReportController::class)
    ->prefix('post-reports')->group(function () {
        Route::post('/', 'createPostReport');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'abilities:user,mod'])->controller(PostReportController::class)
    ->prefix('post-reports')->group(function () {
        Route::delete('/{id}', 'deletePostReport');
    });

// ----------Post Comment
// All roles
Route::middleware(['auth:sanctum'])->controller(PostCommentController::class)
    ->prefix('post-comments')->group(function () {
        Route::get('/{id}', 'getPostCommentById');
        Route::get('/', 'getPostComments');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(PostCommentController::class)
    ->prefix('post-comments')->group(function () {
        Route::post('/', 'createPostComment');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'abilities:user,mod'])->controller(PostCommentController::class)
    ->prefix('post-comments')->group(function () {
        Route::delete('/{id}', 'deletePostComment');
    });

//--------------------------------TIME TABLE---------------------------------
// All roles
Route::middleware(['auth:sanctum'])->controller(TimeTableController::class)
    ->prefix('time-tables')->group(function () {
        Route::get('/user/{user_id}', 'getTimeTablesByUserId');
        Route::get('/{id}', 'getTimeTableById');
        Route::get('/', 'getAllTimeTables');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(TimeTableController::class)
    ->prefix('time-tables')->group(function () {
        Route::post('/', 'createTimeTable');

        Route::put('/{id}', 'updateTimeTable');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(TimeTableController::class)
    ->prefix('time-tables')->group(function () {
        Route::delete('/{id}', 'deleteTimeTable');
    });


//------------------------------------JOB------------------------------------
// ----------Job
// All roles (including guest)
Route::controller(JobController::class)
    ->prefix('jobs')->group(function () {
        Route::get('/{id}', 'getJobById');
        Route::get('/', 'getJobs');
    });

// Only company and employer
Route::middleware(['auth:sanctum', 'ability:company,employer'])->controller(JobController::class)
    ->prefix('jobs')->group(function () {
        Route::post('/', 'createJob');

        Route::put('/stop/{id}', 'stopJob');
        Route::put('/{id}', 'updateJob');
    });

// Only company, employer and moderator
Route::middleware(['auth:sanctum', 'ability:company,employer,mod'])->controller(JobController::class)
    ->prefix('jobs')->group(function () {
        Route::delete('/{id}', 'deleteJob');
    });

// ----------Job Skill
// All roles (including guest)
Route::controller(JobSkillController::class)
    ->prefix('job-skills')->group(function () {
        Route::get('/job/{job_id}', 'getJobSkillsByJobId');
        Route::get('/{id}', 'getJobSkillById');
        Route::get('/', 'getAllJobSkills');
    });

// Only company and employer
Route::middleware(['auth:sanctum', 'ability:company,employer'])->controller(JobSkillController::class)
    ->prefix('job-skills')->group(function () {
        Route::post('/', 'createJobSkill');

        Route::put('/{id}', 'updateJobSkill');
    });

// Only company, employer and moderator
Route::middleware(['auth:sanctum', 'ability:company,employer,mod'])->controller(JobSkillController::class)
    ->prefix('job-skills')->group(function () {
        Route::delete('/{id}', 'deleteJobSkill');
    });

// ------------Job Report
// All roles
Route::middleware(['auth:sanctum'])->controller(JobReportController::class)
    ->prefix('job-reports')->group(function () {
        Route::get('/{id}', 'getJobReportById');
        Route::get('/', 'getJobReports');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(JobReportController::class)
    ->prefix('job-reports')->group(function () {
        Route::post('/', 'createJobReport');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(JobReportController::class)
    ->prefix('job-reports')->group(function () {
        Route::delete('/{id}', 'deleteJobReport');
    });

// ----------Saved Job
// All roles
Route::middleware(['auth:sanctum'])->controller(SavedJobController::class)
    ->prefix('saved-jobs')->group(function () {
        Route::get('/{id}', 'getSavedJobById');
        Route::get('/', 'getSavedJobs');
    });

// Only user
Route::middleware(['auth:sanctum', 'ability:user'])->controller(SavedJobController::class)
    ->prefix('saved-jobs')->group(function () {
        Route::post('/', 'createSavedJob');

        Route::put('/{id}', 'updateSavedJob');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(SavedJobController::class)
    ->prefix('saved-jobs')->group(function () {
        Route::delete('/user-job', 'deleteSavedJobByUserAndJobId');
        Route::delete('/{id}', 'deleteSavedJob');
    });


//-------------Job Category
// All roles (including guest)
Route::controller(JobCategoryController::class)
    ->prefix('job-categories')->group(function () {
        Route::get('/job/{job_id}', 'getJobCategoriesByJobId');
        Route::get('/category/{category_id}', 'getJobCategoriesByCategoryId');
        Route::get('/{id}', 'getJobCategoryById');
        Route::get('/', 'getAllJobCategories');
    });

// Only company and employer
Route::middleware(['auth:sanctum', 'ability:company,employer'])->controller(JobCategoryController::class)
    ->prefix('job-categories')->group(function () {
        Route::post('/', 'createJobCategory');

        Route::put('/{id}', 'updateJobCategory');
    });

// Only company, employer and moderator
Route::middleware(['auth:sanctum', 'ability:company,employer,mod'])->controller(JobCategoryController::class)
    ->prefix('job-categories')->group(function () {
        Route::delete('/{id}', 'deleteJobCategory');
    });

// ------------------------------------CATEGORY------------------------------------
// All roles (including guest)
Route::controller(CategoryController::class)
    ->prefix('categories')->group(function () {
        Route::get('/{id}', 'getCategoryById');
        Route::get('/', 'getCategories');
    });

// Only moderator
Route::middleware(['auth:sanctum', 'ability:mod'])->controller(CategoryController::class)
    ->prefix('categories')->group(function () {
        Route::post('/', 'createCategory');

        Route::put('/{id}', 'updateCategory');

        Route::delete('/{id}', 'deleteCategory');
    });


//------------------------------------COMPANY------------------------------------
// -----------Company Account
// Only moderator
Route::middleware(['auth:sanctum', 'ability:mod'])->controller(CompanyAccountController::class)
    ->prefix('company-accounts')->group(function () {
        Route::get('/', 'getCompanyAccounts');

        Route::put('ban/{id}', 'banCompanyAccount');
        Route::put('unban/{id}', 'unbanCompanyAccount');
        Route::put('lock/{id}', 'lockCompanyAccount');
        Route::put('unlock/{id}', 'unlockCompanyAccount');
        Route::put('verify/{id}', 'verifyCompanyAccount');

        Route::delete('/{id}', 'deleteCompanyAccount');
    });

// Only company
Route::middleware(['auth:sanctum', 'ability:company'])->controller(CompanyAccountController::class)
    ->prefix('company')->group(function () {
        Route::put('/password', 'updatePassword');
    });

// Mod and company
Route::middleware(['auth:sanctum', 'ability:mod,company'])->controller(CompanyAccountController::class)
    ->prefix('company-accounts')->group(function () {
        Route::get('/{id}', 'getCompanyAccountById');
    });

// -----------Company Profile
// All roles (including guest)
Route::controller(CompanyProfileController::class)
    ->prefix('company-profiles')->group(function () {
        Route::get('/{id}', 'getCompanyProfileById');
        Route::get('/', 'getAllCompanyProfiles');
    });

// Only company
Route::middleware(['auth:sanctum', 'ability:company'])->controller(CompanyProfileController::class)
    ->prefix('company-profiles')->group(function () {
        Route::post('/logo/{id}', 'updateCompanyLogo');
        Route::put('/', 'updateCompanyProfile');
    });

// ------------Company Report
// All roles
Route::middleware(['auth:sanctum'])->controller(CompanyReportController::class)
    ->prefix('company-reports')->group(function () {
        Route::get('/{id}', 'getCompanyReportById');
        Route::get('/', 'getCompanyReports');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(CompanyReportController::class)
    ->prefix('company-reports')->group(function () {
        Route::post('/', 'createCompanyReport');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(CompanyReportController::class)
    ->prefix('company-reports')->group(function () {
        Route::delete('/{id}', 'deleteCompanyReport');
    });

// -------------Company Verification
// Only moderator and company
Route::middleware(['auth:sanctum', 'ability:mod,company'])->controller(CompanyVerificationController::class)
    ->prefix('company-verifications')->group(function () {
        Route::get('/company/{company_id}', 'getCompanyVerificationsByCompanyId');
        Route::get('/{id}', 'getCompanyVerificationById');
        Route::get('/', 'getCompanyVerifications');
    });

// Only company
Route::middleware(['auth:sanctum', 'ability:company'])->controller(CompanyVerificationController::class)
    ->prefix('company-verifications')->group(function () {
        Route::post('/', 'createCompanyVerification');
    });

// Only moderator
Route::middleware(['auth:sanctum', 'abilities:mod'])->controller(CompanyVerificationController::class)
    ->prefix('company-verifications')->group(function () {
        Route::put('/approve/{id}', 'approveCompanyVerification');
        Route::put('/reject/{id}', 'rejectCompanyVerification');

        Route::delete('/{id}', 'deleteCompanyVerification');
    });


//------------------------------------CV------------------------------------
// All roles
Route::middleware(['auth:sanctum'])->controller(CVController::class)
    ->prefix('cvs')->group(function () {
        Route::get('/user/{user_id}', 'getCVsByUserId');
        Route::get('/{id}', 'getCVById');
        Route::get('/', 'getAllCVs');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(CVController::class)
    ->prefix('cvs')->group(function () {
        Route::post('/', 'createCV');

        Route::put('/{id}', 'updateCV');
    });

// Only user and moderator
Route::middleware(['auth:sanctum', 'ability:user,mod'])->controller(CVController::class)
    ->prefix('cvs')->group(function () {
        Route::delete('/{id}', 'deleteCV');
    });


//------------------------------------EMPLOYER------------------------------------
// -----------Employer Account
// Only moderator and company
Route::middleware(['auth:sanctum', 'ability:mod,company'])->controller(EmployerAccountController::class)
    ->prefix('employer-accounts')->group(function () {
        Route::put('ban/{id}', 'banEmployerAccount');
        Route::put('unban/{id}', 'unbanEmployerAccount');
        Route::put('lock/{id}', 'lockEmployerAccount');
        Route::put('unlock/{id}', 'unlockEmployerAccount');

        Route::delete('/{id}', 'deleteEmployerAccount');
    });

Route::middleware(['auth:sanctum'])->controller(EmployerAccountController::class)
    ->prefix('employer-accounts')->group(function () {
        Route::get('/', 'getEmployerAccounts');
    });

// Only employer
Route::middleware(['auth:sanctum', 'ability:employer'])->controller(EmployerAccountController::class)
    ->prefix('employer')->group(function () {
        Route::put('/password', 'updatePassword');
    });

// Mod, company and employer
Route::middleware(['auth:sanctum'])->controller(EmployerAccountController::class)
    ->prefix('employer-accounts')->group(function () {
        Route::get('/{id}', 'getEmployerAccountById');
    });

// Only company
Route::middleware(['auth:sanctum', 'ability:company'])->controller(EmployerAccountController::class)
    ->prefix('employer-accounts')->group(function () {
        Route::post('/', 'createEmployerAccount');
    });

// -----------Employer Profile
Route::middleware(['auth:sanctum'])->controller(EmployerProfileController::class)
    ->prefix('employer-profiles')->group(function () {
        Route::get('/{id}', 'getEmployerProfileById');
        Route::get('/', 'getEmployerProfiles');

        Route::post('/avatar/{id}', 'updateEmployerAvatar');
        Route::put('/{id}', 'updateEmployerProfile');
    });


//------------------------------------APPLICATION------------------------------------
// All role
Route::middleware(['auth:sanctum'])->controller(ApplicationController::class)
    ->prefix('applications')->group(function () {
        Route::get('/{id}', 'getApplicationById');
        Route::get('/', 'getApplications');
    });

// Only moderator and user
Route::middleware(['auth:sanctum', 'ability:mod,user'])->controller(ApplicationController::class)
    ->prefix('applications')->group(function () {
        Route::delete('/{id}', 'deleteApplication');
    });

// Only user
Route::middleware(['auth:sanctum', 'abilities:user'])->controller(ApplicationController::class)
    ->prefix('applications')->group(function () {
        Route::post('/', 'createApplication');
    });

// Only company
Route::middleware(['auth:sanctum', 'ability:company,employer'])->controller(ApplicationController::class)
    ->prefix('applications')->group(function () {
        Route::put('/approve/{id}', 'approveApplication');
        Route::put('/reject/{id}', 'rejectApplication');
    });

//------------------------------------AUTH------------------------------------
Route::controller(AuthAdminController::class)
    ->prefix('auth-admin')->group(function () {
        Route::post('/sign-in', 'signIn');
    });

Route::controller(AuthCompanyController::class)
    ->prefix('auth-company')->group(function () {
        Route::post('/sign-up', 'signUp');
        Route::post('/sign-in', 'signIn');
    });

Route::controller(AuthEmployerController::class)
    ->prefix('auth-employer')->group(function () {
        Route::post('/sign-in', 'signIn');
    });

Route::controller(AuthUserController::class)
    ->prefix('auth-user')->group(function () {
        Route::post('/sign-up', 'signUp');
        Route::post('/sign-in', 'signIn');
    });
