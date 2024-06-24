<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportSubject;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.support.index');
    }

    function tickets(Request $rquest)
    {
        $data['tickets'] = Ticket::withTrashed()->latest()->paginate(50);

        return view('admin.support._table', $data);
    }

    function create()
    {
        $data['subjects'] = SupportSubject::latest()->get();
        $data['users'] = User::latest()->get();

        return view('admin.support.create', $data);
    }

    function show(Ticket $ticket)
    {
        $data['ticket'] = $ticket;

        return view('admin.support.show', $data);
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
            'user' => 'required|string|exists:users,uuid',
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

            $user = User::whereUuid($request->user)->first();

            $ticket = Ticket::create([
                'support_subject_id' => $subject->id,
                'user_id' => $user->id,
                'ticket_code' => rand(100000, 999999),
            ]);

            $message = TicketReply::create([
                'ticket_id' => $ticket->id,
                'admin_id'  => Auth::guard('admin')->user()->id,
                'message'   => $request->message
            ]);

            if ($request->hasFile('attachments')) {
                $this->saveAttachment($message, $images);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Ticket created successfully', 'location' => route('admin.support.tickets.index')]);
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
                'admin_id'  => Auth::guard('admin')->user()->id,
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

            return response()->json(['success' => false, 'message' => serviceDownMessage(), 500]);
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

    function deleteTicket(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json(['success' => true, 'message' => 'Ticket deleted successfully']);
    }

    function subjects()
    {
        return view('admin.support.subject.index');
    }

    function subjectsTable()
    {
        $data['subjects'] = SupportSubject::latest()->get();

        return view('admin.support.subject._table', $data);
    }

    function createSubject()
    {
        return view('admin.support.subject.create');
    }

    function storeSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            DB::beginTransaction();

            SupportSubject::create([
                'name' => $request->name
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Subject created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function updateSubject(Request $request, SupportSubject $subject)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            DB::beginTransaction();

            $subject->update([
                'name' => $request->name
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Subject updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function editSubject(SupportSubject $subject)
    {
        $data['subject'] = $subject;

        return view('admin.support.subject.edit', $data);
    }

    function getReplies(Ticket $ticket)
    {
        $data['replies'] = TicketReply::where('ticket_id', $ticket->id)->latest()->get();

        return view('admin.support._replies', $data);
    }

    function closeTicket(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'closed',
        ]);

        return $this->sendResponse([], "Ticket closed successfully.");
    }

    function deleteSubject(SupportSubject $subject)
    {
        $subject->delete();

        return response()->json(['success' => true, 'message' => 'Subject deleted successfully.']);
    }
}
