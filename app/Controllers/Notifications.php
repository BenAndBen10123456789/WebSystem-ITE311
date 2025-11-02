<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use CodeIgniter\API\ResponseTrait;

class Notifications extends BaseController
{
    use ResponseTrait;

    /**
     * Returns a JSON response containing the current user's unread notification count
     * and list of notifications. Called via AJAX.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in.'
            ])->setStatusCode(401);
        }

        $notificationModel = new NotificationModel();
        
        $unreadCount = $notificationModel->getUnreadCount($userId);
        $notifications = $notificationModel->getNotificationsForUser($userId, 5);

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * Accepts a notification ID via POST and marks it as read.
     * Returns a success/failure JSON response.
     *
     * @param int $id Notification ID
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function mark_as_read($id)
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in.'
            ])->setStatusCode(401);
        }

        if (!$id || !is_numeric($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid notification ID.'
            ])->setStatusCode(400);
        }

        $notificationModel = new NotificationModel();
        
        // Verify the notification belongs to the user
        $notification = $notificationModel->find($id);
        if (!$notification || $notification['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found or access denied.'
            ])->setStatusCode(404);
        }

        if ($notificationModel->markAsRead($id)) {
            // Get updated unread count
            $unreadCount = $notificationModel->getUnreadCount($userId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read.',
                'unread_count' => $unreadCount
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notification as read.'
            ])->setStatusCode(500);
        }
    }
}

