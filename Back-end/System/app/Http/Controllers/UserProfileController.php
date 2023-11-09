<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\CompanyProfile;
use App\Models\EmployerProfile;
use App\Models\Job;
use App\Models\UserAchievement;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserProfile;
use App\Models\UserSkill;
use App\Notifications\Invitation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends ApiController
{
    public function getAllUserProfiles(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;

            $user_profiles = UserProfile::with('educations', 'cvs', 'experiences', 'achievements', 'skills', 'time_table')
                ->paginate($count_per_page);

            if (count($user_profiles) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_profiles' => $user_profiles,
                ]);
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getUserProfile(Request $request, string $id): JsonResponse
    {
        try {
            $user_profile = UserProfile::where('id', $id)->with('educations', 'cvs', 'experiences', 'achievements', 'skills', 'time_table')
                ->first();

            if (!$user_profile) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_profile' => $user_profile,
                ]);
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateUserProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        try {
            $user_profile = UserProfile::where('id', $request->user()->id)
                ->with('educations', 'cvs', 'experiences', 'achievements', 'skills', 'time_table')
                ->first();

            if (!$user_profile) {
                return $this->respondNotFound();
            }

            $user_profile->full_name = $request->full_name ?? $user_profile->full_name;
            $user_profile->about_me = $request->about_me ?? $user_profile->about_me;
            $user_profile->good_at_position = $request->good_at_position ?? $user_profile->good_at_position;
            $user_profile->year_of_experience = $request->year_of_experience ?? $user_profile->year_of_experience;
            $user_profile->date_of_birth = $request->date_of_birth ?? $user_profile->date_of_birth;
            $user_profile->gender = $request->gender ?? $user_profile->gender;
            $user_profile->address = $request->address ?? $user_profile->address;
            $user_profile->email = $request->email ?? $user_profile->email;
            $user_profile->phone = $request->phone ?? $user_profile->phone;
            $user_profile->is_private = $request->is_private ?? $user_profile->is_private;

            // upload avatar
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9\-\.]/', '', $file_name);
                $file_name = time() . '_' . $file_name;

                $path = Storage::disk('s3')->putFileAs(
                    'user_avatar',
                    $file,
                    $file_name,
                );
                $url = Storage::disk('s3')->url($path);

                if (!$path || !$url) {
                    return $this->respondInternalServerError('Không thể upload avatar');
                }

                $user_profile->avatar = $url;
            }

            $user_profile->save();

            return $this->respondWithData(
                [
                    'user_profile' => $user_profile,
                ]);
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function importUserProfile(Request $request, string $id): JsonResponse
    {
        try {
            $object = $request->json('object');
            $profile = $object['user_profile'] ?? [];

            $user_profile = UserProfile::updateOrCreate(['id' => $id], $profile);

            if (isset($object['user_profile']['educations'])) {
                $educations = $object['user_profile']['educations'];
                $user_profile->educations()->delete();
                $user_profile->educations()->createMany($educations);
            }

            if (isset($object['user_profile']['experiences'])) {
                $experiences = $object['user_profile']['experiences'];
                $user_profile->experiences()->delete();
                $user_profile->experiences()->createMany($this->formatDates($experiences));
            }

            if (isset($object['user_profile']['achievements'])) {
                $achievements = $object['user_profile']['achievements'];
                $user_profile->achievements()->delete();
                $user_profile->achievements()->createMany($achievements);
            }

            if (isset($object['user_profile']['skills'])) {
                $skills = $object['user_profile']['skills'];
                $user_profile->skills()->delete();
                $user_profile->skills()->createMany($skills);
            }

            return $this->respondWithData(
                [
                    'user_profile' => $user_profile,
                ], 'Cập nhật thông tin thành công'
            );
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateUserAvatar(Request $request, string $id): JsonResponse
    {
        try {
            $user_profile = UserProfile::where('id', $id)->first();

            if (!$user_profile) {
                return $this->respondNotFound();
            }

            // upload avatar
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9\-\.]/', '', $file_name);
                $file_name = time() . '_' . $file_name;

                $path = Storage::disk('s3')->putFileAs(
                    'user_avatar',
                    $file,
                    $file_name,
                );
                $url = Storage::disk('s3')->url($path);

                if (!$path || !$url) {
                    return $this->respondInternalServerError('Không thể upload avatar');
                }

                $user_profile->avatar = $url;
                $user_profile->save();
            }

            return $this->respondWithData(
                [
                    'user_profile' => $user_profile,
                ], 'Cập nhật avatar thành công'
            );
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    private function formatDates($data): array
    {
        return collect($data)->map(function ($item) {
            $item['start'] = Carbon::createFromFormat('d/m/Y', $item['start'])->format('Y-m-d');
            $item['end'] = Carbon::createFromFormat('d/m/Y', $item['end'])->format('Y-m-d');
            return $item;
        })->all();
    }

    public function sendJobInvitationNotification(Request $request): JsonResponse
    {
        try {
            $job_id = $request->job_id;
            $user_id = $request->user_id;
            $refer_link = $request->refer_link;
            $sender = $request->user();

            if ($sender->tokenCan('company')) {
                $company_id = $sender->id;
            } else {
                $company_id = EmployerProfile::where('id', $sender->id)->first()->company_id;
            }

            $company = CompanyProfile::where('id', $company_id)->first();
            if (!$company) {
                return $this->respondNotFound('Không tìm thấy công ty');
            }

            $job = Job::where('id', $job_id)->first();
            if (!$job) {
                return $this->respondNotFound('Không tìm thấy công việc');
            }

            $user_profile = UserProfile::find($user_id);
            if (!$user_profile) {
                return $this->respondNotFound('Không tìm thấy người dùng');
            }

            $user_profile->notify(new Invitation($company->name, $job->title, $refer_link));

            return $this->respondWithData(null, 'Gửi lời mời thành công');
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getUserNotifications(Request $request): JsonResponse
    {
        try {
            $user = UserProfile::find($request->user()->id);

            $notifications = $user->notifications()->paginate(10);

            return $this->respondWithData(
                [
                    'notifications' => $notifications,
                ]
            );
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getUserUnreadNotifications(Request $request): JsonResponse
    {
        try {
            $user = UserProfile::find($request->user()->id);
            $notifications = $user->unreadNotifications()->paginate(10);

            return $this->respondWithData(
                [
                    'notifications' => $notifications,
                ]
            );
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function markNotificationAsRead(Request $request, string $id): JsonResponse
    {
        try {
            $user = UserProfile::find($request->user()->id);
            $notification = $user->notifications()->where('id', $id)->first();

            if (!$notification) {
                return $this->respondNotFound('Không tìm thấy thông báo');
            }

            $notification->markAsRead();

            return $this->respondWithData(null, 'Đánh dấu thông báo thành công');
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function markAllNotificationsAsRead(Request $request): JsonResponse
    {
        try {
            $user = UserProfile::find($request->user()->id);
            $user->unreadNotifications()->update(['read_at' => now()]);

            return $this->respondWithData(null, 'Đánh dấu tất cả thông báo thành công');
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
