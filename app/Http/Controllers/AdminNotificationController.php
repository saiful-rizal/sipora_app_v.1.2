<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $userId = session('auth_user.id_user');

        return DB::table('notifications')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

   public function getLatest()
{
    $userId = session('auth_user.id_user');

    $notifications = DB::table('notifications')
        ->where('user_id', $userId)
        ->orderByDesc('created_at')
        ->limit(20)
        ->get();

    $result = $notifications->map(function ($n) {

        $url = route('admin.dashboard');

        switch ($n->type) {

            case 'user_pending':
                $url = route('admin.users.index');
                break;

            case 'upload':
                $url = route('admin.documents.index');
                break;

            case 'upload_confirm':
                $url = route('admin.documents.index');
                break;
        }

        return [
            'id'         => $n->id,
            'title'      => $n->title,
            'message'    => $n->message,
            'created_at' => $n->created_at,
            'is_read'    => $n->is_read,
            'url'        => $url,
            'icon_class' => $n->icon_class
        ];
    });

    return response()->json($result);
}

    public function count()
    {
        $userId = session('auth_user.id_user');

        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
    }

    public function read($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update([
                'is_read' => 1
            ]);

        return response()->json(['success' => true]);
    }
}