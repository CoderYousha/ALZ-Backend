<?php

namespace App\Http\Services\Notification;

use App\Models\Notification;
use App\Enums\NotificationTypeEnum;

class NotificationService extends BaseNotificationService
{
    private function notify(Notification $notification,array $userIds,array $data=[]){
        $this->init($notification, $data);
        $this->toUserIds = $userIds;
        parent::toFireBase($this->toUserIds,$data);
    }

    // /**
    //  * @param int $userId
    //  * @param Booking $booking
    //  */
    // public function newBooking($userId, Booking $booking){
    //     $data = [
    //         'booking_id' => $booking->id
    //     ];
    //     $notification = Notification::create([
    //         'user_id' => $userId,
    //         'type' => NotificationTypeEnum::NEW_BOOKING->value,
    //         'title_en' => 'New booking',
    //         'title_ar' => 'طلب جديد',
    //         'description_en' => 'A new booking has been received',
    //         'description_ar' => 'تم استلام طلب جديد',
    //         'data' => json_encode($data)
    //     ]);
    //     $this->notify($notification, [$userId], $data);
    // }

    /**
     * @param string $title_en
     * @param string $description_en
     * @param string $title_ar
     * @param string $description_ar
     */
    public function sendToAllUsersByAdmin($title_en, $description_en, $title_ar, $description_ar){
        $notification = Notification::create([
            'user_id' => request()->user()->id,
            'type' => NotificationTypeEnum::GLOBAL_NOTIFICATION->value,
            'title_en' => $title_en,
            'title_ar' => $title_ar,
            'description_en' => $description_en,
            'description_ar' => $description_ar,
            'data' => "[]"
        ]);

        $this->init($notification);
        parent::toFireBaseTopic([], 'global_en', 'en');
        parent::toFireBaseTopic([], 'global_ar', 'ar');
        
    }

}