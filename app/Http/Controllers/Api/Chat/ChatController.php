<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Namu\WireChat\Enums\MessageType;
use Namu\WireChat\Events\MessageCreated;
use Namu\WireChat\Events\NotifyParticipant;
use Namu\WireChat\Models\Conversation;
use Namu\WireChat\Models\Message;
use App\Events\CustomMessageCreated;

class ChatController extends Controller
{
    use ApiResponse;

    // get all chats
    public function chats(Request $request)
    {
        $user = auth()->user();
        $chatQuery = $user->conversations()
            ->with(['participants' => function ($query) use ($user) {
                $query->with('participantable:id,name,avatar');
            }, 'lastMessage' => function ($query) {
                $query->with('attachment');
            },'group']);

        $chat = $chatQuery->get();
        return $this->success($chat, "Chat list fetch successfully", 200);
    }
    // get single chat details
    public function chat($user_id, Request $request)
    {
        $authUser = auth()->user();
        $perPage = $request->get('per_page', 1000);
        $page = $request->get('page', 1);

        $user = User::find($user_id);
        if (!$user) {
            return $this->error([], "User not found", 404);
        }

        // Fetch conversation if it exists
        $conversationQuery = $authUser->conversations()
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('participantable_id', $user->id);
            });
//            $conversationQuery->with(['group']);


        $conversation = $conversationQuery->first();

        if (!$conversation) {
            $conversation = $authUser->createConversationWith($user);
        }

        // Load messages and participants
        $conversation->load([
            'messages' => function ($query) use ($perPage, $page) {
                $query->with('attachment')->latest()->paginate($perPage, ['*'], 'page', $page);
            },
            'participants' => function ($query) {
                $query->with('participantable');
            }
        ]);

        return response()->json([
            'success' => true,
            'message' => "Chat fetched successfully",
            'conversation_id' => $conversation->id,
            'data' => $conversation,
            'code' => 200
        ], 200);
    }


    // send message
    public function sendMessage(Request $request)
    {
        $mediaMimes = config('wirechat.attachments.media_mimes', []);
        $fileMimes = config('wirechat.attachments.file_mimes', []);
        $maxUploadSize = max(
            config('wirechat.attachments.media_max_upload_size', 1024),
            config('wirechat.attachments.file_max_upload_size', 1024)
        );

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'message' => ['required_without:file', 'string'],
            'file' => [
                'required_without:message',
                'file',
                'mimes:' . implode(',', array_merge($mediaMimes, $fileMimes)),
                'max:' . $maxUploadSize,
            ],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            $formUser = auth()->user();
            $toUser = User::find($request->user_id);

            if (!$toUser || $formUser->id === $toUser->id) {
                return $this->error([], "Invalid recipient", 404);
            }

            $conversation = null;


            $conversation = $formUser->conversations()
                ->whereHas('participants', function ($query) use ($toUser) {
                    $query->where('participantable_id', $toUser->id);
                })
                ->first();

            if (!$conversation) {
                $conversation = $formUser->createConversationWith($toUser);
//                $conversation->save();
            }

            if (!$conversation->participants->contains('participantable_id', $toUser->id)) {
                $conversation->addParticipant($toUser);
            }


            // Handle file or text message
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $path = uploadImage($file, 'chat');

                $chat = $conversation->messages()->create([
                    'sendable_type' => get_class($formUser),
                    'sendable_id' => $formUser->id,
                    'type' => MessageType::ATTACHMENT,
                ]);

                $chat->attachment()->create([
                    'file_path' => $path,
                    'file_name' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'url' => Storage::url($path),
                    'type' => $extension,
                    'size' => $size,
                ]);
            } else {
                $chat = $conversation->messages()->create([
                    'body' => $request->message,
                    'sendable_type' => get_class($formUser),
                    'sendable_id' => $formUser->id,
                    'type' => MessageType::TEXT,
                ]);
            }

//            broadcast(new MessageCreated($chat));
            broadcast(new CustomMessageCreated($chat));
            $participant = $chat->conversation->participant($toUser);
            if ($participant) {
//                broadcast(new NotifyParticipant($participant, $chat));
            }


            $chat->conversation->load([
                'messages' => fn ($q) => $q->with('attachment')->latest()->limit(1),
                'participants.participantable'
            ]);
//            dd($conversation);

            DB::commit();
            return $this->success($chat, "Message sent successfully", 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
