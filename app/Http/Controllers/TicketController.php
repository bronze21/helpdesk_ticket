<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Ticket;
use App\Models\TicketAttachments;
use App\Models\TicketsAgent;
use App\Models\TicketsComment;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Str;

class TicketController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->data['crumbs'][1] = [
            'title' => 'Tickets',
            'url' => route('tickets.index'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        $this->data['title'] = "Tickets";
        
        if($role->slug!='user') {
            $this->middleware([EnsureFeaturesAreActive::using('isAdmin'),EnsureFeaturesAreActive::using('isStaff')]);
            return $this->index_staff();
        }
        return $this->user_index();
    }

    public function index_staff()
    {
        $this->data['categories']   = Category::where('isActive',true)->with('subcategories')->get();
        $this->data['datas_url']    = route('tickets.datas.staff');
        return view('pages.tickets.staff.index',$this->data);
    }

    public function datas(Request $request)
    {
        $tickets = Ticket::query();
        
        return DataTables::of($tickets)
        ->addIndexColumn()
        ->addColumn('due_date', fn($data) => $data->due_date->isoFormat('ddd, DD MMM YYYY HH:mm:ss')." WIB")
        ->addColumn('owner', fn($data) => $data->owner->name)
        ->addColumn('topic',fn($data) => "<span class=\"badge bg-info\">{$data->category->name}</span><span class=\"badge bg-secondary\">{$data->code}</span>\n<span class=\"d-block\">{$data->title}</span>")
        ->addColumn('action',function($data){
            return '<a href="'.route('tickets.show',['ticket'=>$data->id]).'" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye me-1"></i>View</a>';
        })
        ->addColumn('status',function($data){
            $statusColor = $data->statusColor[$data->status];
            $statusLabel = ucwords(str_replace('_',' ',$data->status));
            return "<span class='badge bg-{$statusColor} w-100'>{$statusLabel}</span>";
        })
        ->orderColumn('due_date', fn($q,$o)=>$q->orderBy('due_date', $o))
        ->orderColumn('topic', fn($q,$o)=>$q->orderBy('title', $o))
        ->orderColumn('status', fn($q,$o)=>$q->orderBy('status', $o))
        ->order(function($query) use ($request) {
            // dd($request->input());
            if(!$request->has('order')) {
                $query->orderByRaw("(CASE 
                    WHEN priority = 'high' THEN 1 
                    WHEN priority = 'normal' THEN 2 
                    WHEN priority = 'low' THEN 3 
                    ELSE 4 
                END)")->orderBy('due_date','asc');
            }else{
                $query->orderBy($request->input('order.0.name'),$request->input('order.0.dir'));
            }
        })
        ->filter(function($query) use ($request) {
            if($request->has('search.value') && $request->input('search.value')) {
                $query->join('tickets_comments','tickets.id','=','tickets_comments.ticket_id')
                ->join('users as owner','tickets.created_by','=','owner.id')
                ->join('categories','tickets.category_id','=','categories.id')
                ->join('subcategories','tickets.subcategory_id','=','subcategories.id')
                ->where('title','like','%'.$request->input('search.value').'%')
                ->orWhere('code','like','%'.$request->input('search.value').'%')
                ->orWhere('status','like','%'.$request->input('search.value').'%')
                ->orWhere('tickets_comments.messages','like','%'.$request->input('search.value').'%')
                ->orWhere('owner.name','like','%'.$request->input('search.value').'%')
                ->orWhere('categories.name','like','%'.$request->input('search.value').'%')
                ->orWhere('subcategories.name','like','%'.$request->input('search.value').'%')
                ->groupBy('tickets.id')
                ->select('tickets.*');
            }
        })
        ->rawColumns(['topic','action','status'])
        ->make(true);
    }

    public function user_index()
    {
        $this->data['title'] = "Tickets";
        $this->data['datas_url'] = route('tickets.datas.user');
        return view('pages.tickets.users.index',$this->data);
    }

    public function user_datas(Request $request)
    {
        $ticket = Ticket::where('created_by',auth()->user()->id)->latestUpdate()->with(['category','subcategory']);

        return DataTables::of($ticket)
        ->addIndexColumn()
        ->addColumn('latest_update',fn($data) => $data->latest_update->isoFormat('ddd, DD MMM YYYY HH:mm:ss')." WIB")
        ->addColumn('owner', fn($data) => $data->owner->name)
        ->addColumn('topic',fn($data) => "<span class=\"badge bg-info\">{$data->subcategory?->name}</span><span class=\"badge bg-info\">{$data->code}</span>\n<span class=\"d-block\">{$data->title}</span>")
        ->addColumn('action',function($data){
            $url = route('tickets.show',['ticket'=>$data->id]);
            return '<a href="'.$url.'" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye me-1"></i>View</a>';
        })
        ->orderColumn('latest_update', fn($q,$o)=>$q->orderBy('latest_update', $o))
        ->rawColumns(['topic','action'])
        ->filter(function($query) use ($request) {
            if($request->has('search.value') && $request->input('search.value')) {
                $query->join('tickets_comments','tickets.id','=','tickets_comments.ticket_id')
                ->join('users as owner','tickets.created_by','=','owner.id')
                ->join('categories','tickets.category_id','=','categories.id')
                ->join('subcategories','tickets.subcategory_id','=','subcategories.id')
                ->where('title','like','%'.$request->input('search.value').'%')
                ->orWhere('code','like','%'.$request->input('search.value').'%')
                ->orWhere('tickets_comments.messages','like','%'.$request->input('search.value').'%')
                ->orWhere('owner.name','like','%'.$request->input('search.value').'%')
                ->orWhere('categories.name','like','%'.$request->input('search.value').'%')
                ->orWhere('subcategories.name','like','%'.$request->input('search.value').'%')
                ->groupBy('tickets.id')
                ->select('tickets.*');
            }
        })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->middleware(EnsureFeaturesAreActive::using('isUser'));
        $ticketID = strtoupper(Str::random(5).sprintf('%05s', auth()->user()->id));

        $this->data['ticket_id'] = $ticketID;
        $this->data['title'] = 'Create New Ticket #'.$ticketID;
        $this->data['crumbs'][] = [
            'title' => 'Create New Ticket',
            'url' => route('tickets.create'),
        ];
        $this->data['categories'] = Category::where('isActive',true)->with('subcategories')->get();
        $this->data['priorities'] = ['low','normal','high'];

        return view('pages.tickets.users.create',$this->data);
    }

    public function attachments_store (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:jpeg,jpg,png,gif,pdf,docx,xlsx,doc,pptx|max:2048'
        ]);

        if($validator->fails()) return $this->json_error(null,$validator->errors()->first());
        $ticket_code = $request->header('ticket-id');
        $files = [];
        if(is_array($request->file('file'))) {
            foreach($request->file('file') as $file){
                dd($file);
                $files[] = $file->store('ticket_attachments');
            }
        }else{
            $files = $request->file('file')->store("public/ticket_attachments/$ticket_code");
        }
        dd($files);
        return response()->json(['files'=>$files]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'category'=>'required|exists:categories,slug',
            'subcategory'=>'required|exists:subcategories,slug',
            'ticket_code'=>'required|unique:tickets,code',
            'title'=>'required|min:3',
            'message'=>'required|min:20',
            'file'=>'array|min:0',
            'priority'=>'required|in:low,normal,high',
        ];
        if($request->hasFile('file')) {
            $rules['file'] = 'array|min:1';
            $rules['file.*'] = 'mimes:jpeg,jpg,png,gif,pdf,docx,xlsx,doc,pptx|max:2048';
        }
        $request->validate($rules);

        try {
            DB::beginTransaction();
            $newTicket = new Ticket();
            $newTicket->category_id = Category::where('slug',$request->category)->first()->id;
            $newTicket->subcategory_id = Subcategory::where('slug',$request->subcategory)->first()->id;
            $newTicket->title = $request->title;
            $newTicket->code = $request->ticket_code;
            $newTicket->priority = $request->priority;
            $newTicket->status = 'open';
            $newTicket->due_date = now()->addHours(3);
            $newTicket->created_by = auth()->user()->id;
            $newTicket->save();

            $newTicketComment = new TicketsComment();
            $newTicketComment->ticket_id = $newTicket->id;
            $newTicketComment->messages = $request->message;
            $newTicketComment->user_id = auth()->user()->id;
            $newTicketComment->user_type = auth()->user()->role->slug;
            $newTicketComment->save();

            if($request->hasFile('file')) {
                $files = $request->file('file');
                $uploaded_files = [];
                if(is_array($request->file('file'))) {
                    foreach($request->file('file') as $file){
                        $file_location = $file->store('public/ticket_attachments');
                        $uploaded_files[] = (object) [
                            'file_path' => $file_location,
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'file_type' => $file->getClientMimeType(),
                        ];
                    }
                }else{
                    $file_location = $request->file('file')->store("public/ticket_attachments/$request->ticket_code");
                    $uploaded_files[] = (object)[
                        'file_path' => $file_location,
                        'name' => $request->file('file')->getClientOriginalName(),
                        'size' => $request->file('file')->getSize(),
                        'file_type' => $request->file('file')->getClientMimeType(),
                    ];
                }

                foreach($uploaded_files as $upFiles){
                    $newAttachment = new Attachment();
                    $newAttachment->upload_by = auth()->user()->id;
                    $newAttachment->path = $upFiles->file_path;
                    $newAttachment->name = $upFiles->name;
                    $newAttachment->size = $upFiles->size;
                    $newAttachment->type = $upFiles->file_type;
                    $newAttachment->save();

                    $newTicketAttachment = new TicketAttachments();
                    $newTicketAttachment->user_id = auth()->user()->id;
                    $newTicketAttachment->ticket_id = $newTicket->id;
                    $newTicketAttachment->ticket_comment_id = $newTicketComment->id;
                    $newTicketAttachment->attachment_id = $newAttachment->id;
                    $newTicketAttachment->save();
                }
            }
            DB::commit();
            return redirect()->route('tickets.index')->with('success','Ticket has been created');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
    }

    public function store_reply(Request $request, Ticket $ticket)
    {
        $rules = [
            'message'=>'required|min:20',
            'file'=>'array|min:0',
        ];
        if($request->hasFile('file')) {
            $rules['file'] = 'array|min:1';
            $rules['file.*'] = 'mimes:jpeg,jpg,png,gif,pdf,docx,xlsx,doc,pptx|max:2048';
        }
        $request->validate($rules);

        try {
            DB::beginTransaction();
            $newTicket = $ticket;

            $newTicketComment = new TicketsComment();
            $newTicketComment->ticket_id = $newTicket->id;
            $newTicketComment->messages = $request->message;
            $newTicketComment->user_id = auth()->user()->id;
            $newTicketComment->user_type = auth()->user()->role->slug;
            $newTicketComment->save();

            if(auth()->user()->role->slug != 'user') {
                $newTicketAgent = new TicketsAgent();
                $newTicketAgent->user_id = auth()->user()->id;
                $newTicketAgent->due_date = now()->addHours(3);
                $newTicketAgent->ticket_id = $newTicket->id;
                $newTicketAgent->save();

                $newTicket->status = 'on_progress';
                $newTicket->save();
            }

            if($request->hasFile('file')) {
                $files = $request->file('file');
                $uploaded_files = [];
                if(is_array($request->file('file'))) {
                    foreach($request->file('file') as $file){
                        $file_location = $file->store('public/ticket_attachments');
                        $uploaded_files[] = (object) [
                            'file_path' => $file_location,
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'file_type' => $file->getClientMimeType(),
                        ];
                    }
                }else{
                    $file_location = $request->file('file')->store("public/ticket_attachments/$request->ticket_code");
                    $uploaded_files[] = (object)[
                        'file_path' => $file_location,
                        'name' => $request->file('file')->getClientOriginalName(),
                        'size' => $request->file('file')->getSize(),
                        'file_type' => $request->file('file')->getClientMimeType(),
                    ];
                }

                foreach($uploaded_files as $upFiles){
                    $newAttachment = new Attachment();
                    $newAttachment->upload_by = auth()->user()->id;
                    $newAttachment->path = $upFiles->file_path;
                    $newAttachment->name = $upFiles->name;
                    $newAttachment->size = $upFiles->size;
                    $newAttachment->type = $upFiles->file_type;
                    $newAttachment->save();

                    $newTicketAttachment = new TicketAttachments();
                    $newTicketAttachment->user_id = auth()->user()->id;
                    $newTicketAttachment->ticket_id = $newTicket->id;
                    $newTicketAttachment->ticket_comment_id = $newTicketComment->id;
                    $newTicketAttachment->attachment_id = $newAttachment->id;
                    $newTicketAttachment->save();
                }
            }
            DB::commit();
            return redirect()->route('tickets.show',$ticket->id)->with('success','Ticket has been replied');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $this->data['title'] = "Edit Ticket #$ticket->code";
        $this->data['crumbs'][] = [
            'title' => 'Edit Ticket',
            'url' => route('tickets.edit',[$ticket->id]),
        ];
        $this->data['data'] = Ticket::where('tickets.id',$ticket->id)->latestUpdate()->with(['comments'=>fn($q) => $q->orderBy('updated_at','desc')])->first();
        $this->data['categories'] = Category::where('isActive',true)->with('subcategories')->get();
        $this->data['priorities'] = ['low','normal','high'];

        return view('pages.tickets.show',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $this->data['title'] = "Edit Ticket";
        $this->data['crumbs'][] = [
            'title' => 'Edit Ticket',
            'url' => route('tickets.edit',[$ticket->id]),
        ];
        $this->data['data'] = $ticket;

        return view('pages.tickets.edit',$this->data);
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(),[
            'status'=>'required|in:on_progress,closed' 
        ]);

        if($validator->fails()) return $this->json_error(null,$validator->errors()->first());

        try {
            DB::beginTransaction();
            $ticket->status = $request->status;
            $ticket->save();
            DB::commit();
            return $this->json_success(null,'Ticket has been updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
