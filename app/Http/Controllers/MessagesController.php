<?php

namespace App\Http\Controllers;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class MessagesController extends Controller
{

    public function getConversations()
    {
        $userId = Auth::id();

        $conversations = DB::table('conversations')
            ->where('user1_id', $userId)
            ->orWhere('user2_id', $userId)
            ->leftJoin('users as u1', 'conversations.user1_id', '=', 'u1.id')
            ->leftJoin('users as u2', 'conversations.user2_id', '=', 'u2.id')
            ->select(
                'conversations.id',
                DB::raw('CASE WHEN conversations.user1_id = ' . $userId . ' THEN u2.id ELSE u1.id END AS conversation_partner_id'),
                DB::raw('CASE WHEN conversations.user1_id = ' . $userId . ' THEN u2.name ELSE u1.name END AS conversation_partner_name'),
                DB::raw('CASE WHEN conversations.user1_id = ' . $userId . ' THEN u2.profile_photo_path ELSE u1.profile_photo_path END AS conversation_partner_photo'),

            )
            ->get();

        return Inertia::render('Messages/Conversations', compact('conversations'));
    }
    public function toggleConversation($id){

        $user1_id = Auth::id();
        if($this->exitsConversation($id)){
           return redirect()->route('conversations');
        }else{
            DB::table('conversations')->insert([
                "user1_id" => $user1_id,
                "user2_id" => $id
            ]);
            return redirect()->route('conversations');
        }
    }
    public function exitsConversation($id){

        $user1_id = Auth::id();
        $user2_id = $id;

        $existConversation = DB::table('conversations')
            ->where(function ($query) use ($user1_id, $user2_id) {
                $query->where('user1_id', $user1_id)
                    ->where('user2_id', $user2_id);
            })
            ->orWhere(function ($query) use ($user1_id, $user2_id) {
                $query->where('user1_id', $user2_id)
                    ->where('user2_id', $user1_id);
            })
            ->exists();

        return $existConversation;
    }

    public function index($conversationId)
    {
        $messages = DB::table('messages')
            ->where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function store(Request $request, $conversationId)
    {
        $conversationPartnerId = $request->input("conversationPartnerId");
        $conversationPartnerName = $request->input("conversationPartnerName");

        $user_id = $request->input("user_id");
        $messageId = DB::table('messages')->insertGetId([
            'conversation_id' => $conversationId,
            'sender_id' => $user_id,
            'content' => $request->input('content'),
            'created_at' => now()
        ]);

        $name_notifica = DB::table('users')
            ->select('name')
            ->where("id", $user_id)
            ->first();


        DB::table("notifications")->insert([
            "user_id" => $conversationPartnerId,
            "user_name" => $conversationPartnerName,
            "notifica_id" => $user_id,
            "notifica_name" => $name_notifica->name,
            "type" => 'message',
            "recipe_id" => null,
            "created_at" => Carbon::now()
        ]);


        $messageData = DB::table('messages')->where('id', $messageId)->first();


        return response()->json(['message' => $messageData], 201);
    }




}
