<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportSubject;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    public $allowedExtension = array('');

    function __construct()
    {
        $this->allowedExtension = array('jpg', 'png', 'jpeg', 'pdf');
    }

    function index()
    {
        return view('user.support.index');
    }

    function tickets(Request $request)
    {
        $user = $request->user();

        $data['tickets'] = Ticket::where('user_id', $user->id)->latest()->paginate(50);

        return view('user.support._table', $data);
    }

    function create()
    {
        $data['subjects'] = SupportSubject::latest()->get();

        return view('user.support.create', $data);
    }

    function show(Ticket $ticket)
    {
        $data['ticket'] = $ticket;

        return view('user.support.show', $data);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    function store(Request $request)
    {
        $images = $request->file('attachments');

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|exists:support_subjects,uuid',
            'message' => 'required|string|',
            'attachments' => [
                'nullable',
                'max:4096',
                function ($attribute, $value, $fail) use ($images) {
                    $ext = strtolower($images->getClientOriginalExtension());
                    validdateFile($images, $ext, $this->allowedExtension);
                },
            ],
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            DB::beginTransaction();

            $subject = SupportSubject::whereUuid($request->subject)->first();

            $user = $request->user();

            $ticket = Ticket::create([
                'support_subject_id' => $subject->id,
                'user_id' => $user->id,
                'ticket_code' => rand(100000, 999999),
                'status' => 'open'
            ]);

            $message = TicketReply::create([
                'ticket_id' => $ticket->id,
                'message'   => $request->message
            ]);

            if ($request->hasFile('attachments')) {
                $this->saveAttachment($message, $images);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Ticket created successfully', 'location' => route('tickets.index')]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function replyTicket(Request $request, Ticket $ticket)
    {
        $images = $request->file('attachments');

        $validator = Validator::make($request->all(), [
            'message'   => ['required', 'string'],
            'attachments' => [
                'nullable',
                'max:4096',
                function ($attribute, $value, $fail) use ($images) {
                    $ext = strtolower($images->getClientOriginalExtension());
                    validdateFile($images, $ext, $this->allowedExtension);
                },
            ],
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            DB::beginTransaction();

            $message = TicketReply::create([
                'ticket_id' => $ticket->id,
                'admin_id'  => null,
                'message'   => $request->message
            ]);

            if ($request->hasFile('attachments')) {
                $this->saveAttachment($message, $request->file('attachments'));
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Ticket replied successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function saveAttachment(TicketReply $message, $file)
    {
        $url = uploadFile($file, "uploads/tickets", "do_spaces");

        TicketAttachment::create([
            'ticket_replies_id' => $message->id,
            'file_url'  => $url
        ]);
    }

    function update(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'status' => $request->status
        ]);

        return response()->json(['success' => true, 'message' => 'Ticket updated successfully']);
    }

    function getReplies(Ticket $ticket)
    {
        $data['replies'] = TicketReply::where('ticket_id', $ticket->id)->latest()->get();

        return view('user.support._replies', $data);
    }

    function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json(['success' => true, 'message' => 'Ticket deleted successfully.']);
    }
}
