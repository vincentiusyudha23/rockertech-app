<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Precense;
use App\Models\Todolist;
use Carbon\CarbonPeriod;
use App\Models\Esp32Mode;
use Illuminate\View\View;
use App\Models\BackupData;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use App\Models\StaticOption;
use App\Models\TimePrecense;
use Illuminate\Http\Request;
use App\Events\PrecenseEvent;
use App\Models\MediaUploader;
use App\Models\KeyPerformance;
use App\Models\PermitSubmission;
use Illuminate\Validation\Rules;
use App\Events\RegisterCardEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{

    public function index()
    {
        $precense = Precense::todayPrecense()->latest()->get();
        $employe = Employee::all()->count();
        return view('admin.dashboard.index', compact('precense','employe'));
    }

    public function profile(Request $request): View
    {
        return view('admin.auth.profile');
    }

    public function change_password(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password Update Successfully');
    }

    public function employee_acct()
    {
        $employees = Employee::orderBy('id', 'desc')->get();

        $select_employ = [];

        foreach ($employees as $item) {
            if(empty($item?->card_id)){
                $select_employ[$item->id] = $item->name;
            }
        }

        return view('admin.employee.new-view', compact('employees', 'select_employ'));
    }

    public function employee_store(Request $request)
    {
        $this->validate($request,[
            'name' => ['required','max:255'],
            'position' => ['required','max:255'],
            'email' => ['required','email','max:50','unique:users,email'],
            'mobile' => ['required','max:20'],
            'nik' => ['required'],
            'date_birth' => ['required'],
            'address' => ['nullable'],
            'kelurahan' => ['nullable'],
            'kecamatan' => ['nullable'],
            'kota' => ['nullable'],
            'provinsi' => ['nullable'],
            'image' => ['nullable']
        ]);

        DB::beginTransaction();
        try{
            $firstname = explode(' ',$request->name)[0];
            $username = Str::slug($firstname);
            $password = preg_replace('/[^0-9]/', '', $request->date_birth);

            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'role' => 'employee',
                'password' => Hash::make($password)
            ]);

            if($user) {
                $employe = Employee::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'position' => $request->position,
                    'email' => $request->email,
                    'nik' => $request->nik,
                    'mobile' => $request->mobile,
                    'birthday' => $request->date_birth,
                    'image' => $request->image,
                    'enc_password' => Crypt::encryptString($password)
                ]);

                $user->assignRole('employee');
    
                if($employe && $request->address){
                    UserAddress::create([
                        'employee_id' => $employe->id,
                        'street_address' => $request->address,
                        'kelurahan' => $request->kelurahan,
                        'kecamatan' => $request->kecamatan,
                        'kota' => $request->kota,
                        'provinsi' => $request->provinsi
                    ]);
                }

                DB::commit();
                return redirect()->back()->with('success', 'Create Data Employee Successfuly.');
            }

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }

    }

    public function edit_employe($id)
    {
        $employe = Employee::find($id);

        return view('admin.employee.edit', compact('employe'));
    }

    public function update_employe(Request $request)
    {
        $this->validate($request,[
            'id' => ['required'],
            'name' => ['required','max:255'],
            'position' => ['required','max:255'],
            'email' => ['required','email','max:50'],
            'mobile' => ['required','max:20'],
            'nik' => ['required'],
            'date_birth' => ['required'],
            'address' => ['nullable'],
            'kelurahan' => ['nullable'],
            'kecamatan' => ['nullable'],
            'kota' => ['nullable'],
            'provinsi' => ['nullable'],
            'image' => ['nullable']
        ]);

        $employee = Employee::find($request->id);
        DB::beginTransaction();
        try{
            $employee->update([
                'name' => $request->name,
                'position' => $request->position,
                'email' => $request->email,
                'nik' => $request->nik,
                'mobile' => $request->mobile,
                'birthday' => $request->date_birth,
                'image' => $request->image,
            ]);

            if($request->address){
                $address = UserAddress::updateOrCreate(
                    ['employee_id' => $employee->id],
                    [   
                        'employee_id' => $employee->id,
                        'street_address' => $request->address,
                        'kelurahan' => $request->kelurahan,
                        'kecamatan' => $request->kecamatan,
                        'kota' => $request->kota,
                        'provinsi' => $request->provinsi
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Update Successfully');

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }

    }

    public function delete_employe($id)
    {
        $employe = Employee::find($id);

        if($employe){
            User::find($employe->user_id)->delete();
            $employe->delete();

            return redirect()->back()->with('success', 'Deleted Successfully');
        }

        return redirect()->back()->with('errors', 'Employee Not Found');
    }

    public function requestUpdateCardEdit(Request $request)
    {
        $employee = Employee::find($request->employee_id);

        $employee->card_id = $request->card_id;
        $employee->save();

        return response()->json([
            'type' => 'success',
            'msg' => 'Update Card Id Successfully' 
        ]);
    }

    public function updateCardId(Request $request)
    {
        $this->validate($request,[
            'employee_id' => 'required|max:8',
            'card_id' => 'required|max:25'
        ]);

        $employee = Employee::find($request->employee_id);

        if(!$employee){
            return response()->json([
                'type' => 'error',
                'msg' => 'Employee is Not Found!' 
            ]);
        }

        try{
            $employee->card_id = $request->card_id;
            $employee->save();

            Esp32Mode::setPrecense()->setOffRegis();

            return response()->json([
                'type' => 'success',
                'msg' => 'Register Success',
                'card_id' => $employee->card_id
            ]);

        }catch(\Exception $e){
            return response()->json([
                'type' => 'error',
                'msg' => $e->getMessage() 
            ]);
        };
    }

    public function set_action_mode(string $id)
    {   
        if($id == 2){
            Esp32Mode::setRegis()->setOffPrecense();
        }else{
            Esp32Mode::setPrecense()->setOffRegis();
        }

        return response()->json([
            'type' => 'success'
        ]);
    }

    public function requestEsp(Request $request)
    {
        $card_id = $request->input('card_id');

        if(Esp32Mode::getStatusRegis()){
            $employe = Employee::where('card_id', $card_id)->first();
            if($employe){
                event(new RegisterCardEvent('error'));
                return response()->json([
                        'status' => true,
                        'message' => 'Card Already Use',
                        'sound' => 0
                    ]);
            }else{
                event(new RegisterCardEvent($card_id));
            }
        }

        if(Esp32Mode::getStatusPrecense()){
            
            $user = Employee::where('card_id', $card_id)->first();

            if($user){
                $time = TimePrecense::where('type', 'settings')->first();
                
                if(!$time){
                    return response()->json([
                        'status' => true,
                        'message' => 'Need Settings Time Precense First',
                        'sound' => 0
                    ]);
                }

                $precense = Precense::where('employe_id', $user->id)
                            ->where('type' , 1)
                            ->where(function ($query) {
                                $query->where('status', 1)
                                    ->orWhere('status', 2);
                            })
                            ->whereDate('created_at', Carbon::now())->exists();

                if($precense && Carbon::now()->format('H:i') <= $time?->min_out_office){
                    return response()->json([
                        'status' => true,
                        'message' => 'Not Valid',
                        'sound' => 0
                    ]);
                }else{
                    $status = 0;
                    $type = 1;
                    $sound = 1;
                    if(Carbon::now()->format('H:i') <= $time?->min_in_office || Carbon::now()->format('H:i') >= $time?->min_in_office && Carbon::now()->format('H:i') <= $time?->max_in_office){
                        $status = 1;
                    }

                    if(Carbon::now()->format('H:i') >= $time?->max_in_office && Carbon::now()->format('H:i') <= $time?->min_out_office){
                        $status = 2;
                    }

                    if(Carbon::now()->format('H:i') >= $time?->min_out_office){
                        $out_today = Precense::where('employe_id', $user->id)
                                        ->where('type', 2)
                                        ->whereDate('created_at', Carbon::now())
                                        ->exists();

                        if($out_today){
                            return response()->json([
                                'status' => true,
                                'message' => 'Not Valid',
                                'sound' => 0
                            ]);
                        }

                        $type = 2;
                        $status = 1;
                        $sound = 2;
                    }

                    $new_precense = Precense::create([
                        'employe_id' => $user->id,
                        'type' => $type,
                        'status' => $status,
                        'time' => Carbon::now()->format('H:i'),
                    ]);

                    $today_precense = Precense::todayPrecense()->get();

                    $data = [
                        'image' => get_data_image($new_precense->employe->image)['img_url'] ?? '',
                        'name' => $new_precense->employe->name,
                        'time' => carbon::parse($new_precense->time)->format('H:i:s'),
                        'timeHuman' => carbon::parse($new_precense->time)->diffForHumans(),
                        'today_total' => $today_precense->count(),
                        'status' => labelStatus($new_precense->status),
                        'position' => $new_precense->employe->position,
                        'late' => $today_precense->where('status', 2)->count(),
                        'absen' => $today_precense->where('status', 3)->count()
                    ];

                    event(new PrecenseEvent($data));

                    return response()->json([
                        'status' => true,
                        'message' => 'Precense Success',
                        'status_time' => $status,
                        'sound' => $sound
                    ], 200);
                }
            }else{
                return response()->json([
                    'status' => true,
                    'message' => 'Invalid Card',
                    'sound' => 0
                ], 404);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Register Success',
            'sound' => 0
        ], 200);
    }

    public function requestPresence(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120',
            'nik' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => true,
                'type' => 'error',
                'message' => 'Validation is Failed',
                'errors' => $validator->errors() 
            ], 422);
        }

        try {
            $time = TimePrecense::where('type', 'settings')->first();
            $employe = Employee::where('nik', $request->input('nik'))->first();

            if(!$time){
                return response()->json([
                    'status' => true,
                    'type' => 'error',
                    'message' => 'Need Settings Time Precense First',
                ], 422);
            }

            if(!$employe){
                return response()->json([
                    'status' => true,
                    'type' => 'error',
                    'message' => 'Employee is Not Found',
                ], 422);
            }

            $precense = $employe->precense()
                ->where('type', 1)
                ->where(function($query){
                    $query->where('status', 1)->orWhere('status', 2);
                })
                ->whereDate('created_at', Carbon::now())
                ->exists();
            if($precense && Carbon::now()->format('H:i') <= $time?->min_out_office){
                return response()->json([
                    'status' => true,
                    'type' => 'error',
                    'message' => 'Not Valid'
                ], 422);
            } else {
                $status = 0;
                $type = 1;
                $image_id = null;

                if(Carbon::now()->format('H:i') <= $time?->min_in_office || Carbon::now()->format('H:i') >= $time?->min_in_office && Carbon::now()->format('H:i') <= $time?->max_in_office){
                    $status = 1;
                }
                if(Carbon::now()->format('H:i') >= $time?->max_in_office && Carbon::now()->format('H:i') <= $time?->min_out_office){
                    $status = 2;
                }
                if(Carbon::now()->format('H:i') >= $time?->min_out_office){
                    $out_today = $employe->precense()
                                ->where('type', 2)
                                ->whereDate('created_at', Carbon::now())
                                ->exists();

                    if($out_today){
                        return response()->json([
                            'status' => true,
                            'type' => 'error',
                            'message' => 'Not Valid',
                        ], 422);
                    }

                    $type = 2;
                    $status = 1;
                }

                if($request->hasFile('file')){
                    $image = $request->file;
                    $image_extension = $image->extension();
                    $image_name_with_ext = $image->getClientOriginalName();
    
                    $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
                    $image_name = strtolower(Str::slug($image_name));
    
                    $image_db = $image_name.time().'.'.$image_extension;
                    $folder_path = global_assets_path('assets/img/employes');
                    $image->move($folder_path, $image_db);
    
                    if($image) {
                        $mediaData = MediaUploader::create([
                            'title' => $image_name_with_ext,
                            'path' => $image_db,
                            'size' => null,
                            'user_id' => $employe->user->id
                        ]);

                        if($mediaData){
                            $image_id = $mediaData->id;
                        }
                    }
                }

                $new_precense = $employe->precense()->create([
                    'type' => $type,
                    'status' => $status,
                    'time' => Carbon::now()->format('H:i'),
                    'image' => $image_id,
                ]);

                $today_precense = Precense::todayPrecense()->get();

                $data = [
                    'image' => get_data_image($new_precense->employe->image)['img_url'] ?? '',
                    'name' => $new_precense->employe->name,
                    'time' => carbon::parse($new_precense->time)->format('H:i:s'),
                    'timeHuman' => carbon::parse($new_precense->time)->diffForHumans(),
                    'today_total' => $today_precense->count(),
                    'status' => labelStatus($new_precense->status),
                    'position' => $new_precense->employe->position,
                    'late' => $today_precense->where('status', 2)->count(),
                    'absen' => $today_precense->where('status', 3)->count()
                ];

                event(new PrecenseEvent($data));

                return response()->json([
                    'status' => true,
                    'type' => 'success',
                    'message' => 'Precense is Successfully',
                    'status_time' => $status
                ], 200);
            }
            
        } catch (\Exception $e){
            return response()->json([
                'status' => true,
                'type' => 'error',
                'message' => 'Something Went Wrong',
                'errors' => $e->getMessage(),
                'line' => $e->getLine()
            ], 422);
        }
    }

    public function presence()
    {
        $precenses = Precense::orderBy('created_at', 'desc')->get();
        $employes = Employee::select('name', 'id')->get();
        return view('admin.presence.index', compact('precenses', 'employes'));
    }

    public function settings()
    {

        $time = TimePrecense::where('type', 'settings')->latest()->first();
        $settings = [
            // [
            //     'title' => 'Token API',
            //     'icon' => 'fa-solid fa-key',
            //     'key' => 'api-token',
            //     'route' => '#',
            //     'methode' => '',
            //     'field' => [
            //         [
            //             'type' => 'text',
            //             'title' => 'Token',
            //             'option' => 'readonly',
            //             'value' => get_static_option('api_token', '')
            //         ]
            //     ],
            //     'button' => false
            // ],
            [
                'title' => 'Alarm',
                'icon' => 'fa-solid fa-bell',
                'key' => '',
                'route' => route('admin.update-static-option'),
                'methode' => '',
                'field' => [
                    [
                        'type' => 'time',
                        'title' => 'Rest Time',
                        'name' => 'alarm_rest_time',
                        'value' => get_static_option('alarm_rest_time', ''),
                    ],
                    [
                        'type' => 'time',
                        'title' => 'Off Rest Time',
                        'name' => 'alarm_off_rest_time',
                        'value' => get_static_option('alarm_off_rest_time', ''),
                    ],
                    [
                        'type' => 'time',
                        'title' => 'Out Office',
                        'name' => 'alarm_out_office',
                        'value' => get_static_option('alarm_out_office',''),
                    ],
                ],
                'button' => true
            ],
            [
                'title' => 'Time Precense',
                'icon' => 'fa-solid fa-clock',
                'key' => 'time_precense_settings',
                'route' => route('admin.update-time-precense'),
                'methode' => '',
                'field' => [
                    [
                        'type' => 'time',
                        'title' => 'Min Time In Office',
                        'name' => 'min_in_office',
                        'value' => $time?->min_in_office,
                    ],
                    [
                        'type' => 'time',
                        'title' => 'Max Time In Office',
                        'name' => 'max_in_office',
                        'value' => $time?->max_in_office,
                    ],
                    [
                        'type' => 'time',
                        'title' => 'Min Time Out Office',
                        'name' => 'min_out_office',
                        'value' => $time?->min_out_office,
                    ],
                ],
                'button' => true
            ],
            [
                'title' => 'Quote',
                'icon' => 'fa-solid fa-quote-left',
                'key' => 'time_precense_settings',
                'route' => route('admin.update-static-option'),
                'methode' => '',
                'field' => [
                    [
                        'type' => 'textarea',
                        'title' => 'Quote For Employee',
                        'name' => 'quote_employe',
                        'value' => get_static_option('quote_employe')
                    ]
                ],
                'button' => true
            ]
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function make_token_api()
    {
        update_static_option('api_token',  Auth::user()->createToken('token', ['*'])->plainTextToken);

        return redirect()->back()->with('success', 'Create Token Successfully');
    }

    public function setTimePrecense(Request $request)
    {
        $this->validate($request, [
            'min_in_office' => 'required',
            'max_in_office' => 'required',
            'min_out_office' => 'required'
        ]);
        
        try{
            TimePrecense::updateOrCreate(
                ['type' => 'settings'],
                [
                    'type' => 'settings',
                    'min_in_office' => $request->min_in_office,
                    'max_in_office' => $request->max_in_office,
                    'min_out_office' => $request->min_out_office
                ]
            );

            return response()->json([
                'type' => 'success',
                'msg' => 'Update Successfully'
            ]);

        } catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function update_static_uption(Request $request)
    {
        $data = $request->all();

        foreach ($data ?? [] as $key => $value) {
            update_static_option($key, $value);
        }

        return response()->json([
            'type' => 'success',
            'msg' => 'Update Successfully'
        ]);
    }

    public function get_status_alarm()
    {
        $alarm = StaticOption::where('option_name', 'alarm')->select('option_value')->first();

        if($alarm){
            $status = $alarm->option_value;
        }else{
            $status = 0;
        }

        return response()->json([
            'status' => $status
        ], 200);
    }

    public function alarm_status_zero()
    {
        StaticOption::updateOrCreate(
            ['option_name' => 'alarm'],
            ['option_name' => 'alarm', 'option_value' => 0]
        );

        return response()->json([
            'status' => true
        ],200);
    }

    public function set_status_alarm($status)
    {
        \Log::info('Status Alarm is'.$status);

        StaticOption::updateOrCreate(
            ['option_name' => 'alarm'],
            ['option_name' => 'alarm', 'option_value' => $status]
        );

        return true;
    }

    public function file_report()
    {
        $backup = BackupData::latest()->get();

        return view('admin.file-report.index', compact('backup'));
    }

    public function export_precense(Request $request)
    {
        $this->validate($request,[
            'employe' => 'required',
            'from_date' => 'nullable',
            'end_date' => 'nullable'
        ]);

        $employe = $request->employe;
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $end_date =  Carbon::parse($request->end_date)->format('Y-m-d');

        $precense = Precense::query()
                ->when($employe, function($query, $employe){
                    if(in_array('all', $employe)){
                        return $query->latest();
                    }else{
                        return $query->whereIn('employe_id', $employe);
                    }
                })
                ->when($from_date, function($query, $from_date){
                    return $query->whereDate('created_at', '>=' , $from_date);
                })
                ->when($end_date, function($query, $end_date){
                    return $query->whereDate('created_at', '<=' , $end_date);
                })
                ->get();
        
        $name_file = 'Precense_report.xlsx';
        if($request->from_date || $request->end_date){
            $name_file = 'Precense_'.($request->from_date ?? '0-0-0').'_to_'.($request->end_date ?? '0-0-0').'.xlsx';
        }

        return (new FastExcel($precense))->download($name_file, function($precense){
            return [
                'Name' => $precense?->employe?->name,
                'Position' => $precense?->employe?->position,
                'Type' => labelTypeString($precense->type),
                'Status' => labelStatusString($precense->status),
                'Time' => $precense?->time,
                'Date' => $precense?->created_at->format('d-m-Y')
            ];
        });
    }

    public function getPrecenseSheet()
    {
        return Precense::latest()->get()->transform(function($precense){
            return [
                'Name' => $precense?->employe?->name,
                'Position' => $precense?->employe?->position,
                'Type' => labelTypeString($precense->type),
                'Status' => labelStatusString($precense->status),
                'Time' => $precense?->time,
                'Date' => $precense?->created_at->format('d-m-Y')
            ];
        })->toJson();
    }

    public function setAbsenPrecense()
    {
        $precense_today = Precense::todayPrecense()->pluck('employe_id')->toArray();

        $employe = Employee::whereNotIn('id', $precense_today)->select('id')->get();

        if($employe){
            foreach($employe as $val){
                Precense::create([
                    'employe_id' => $val->id,
                    'type' => 1,
                    'status' => 3,
                    'time' => Carbon::now()->format('H:i'),
                ]);
            }
        }

        return true;
    }

    // private function getTodolistData()
    // {
    //     return Todolist::orderBy('created_at', 'desc')->get()->map(function($item){
    //         return [
    //             'id' => $item->id,
    //             'user_image' => get_data_image($item->employe?->image)['img_url'],
    //             'user_name' => $item->employe->name,
    //             'title' => $item->title,
    //             'dueDate' => $item->due_date->format('D, d M Y'),
    //             'priority' => $item->priority,
    //             'status' => $item->status,
    //             'description' => $item->desc
    //         ];
    //     });
    // }

    public function todolist()
    {
        $todolist = Todolist::orderBy('created_at', 'desc')->get();
        return view('admin.todolist.index', compact('todolist'));
    }

    public function set_completed_todolist(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        
        $id = $request->id;
        $todolist = Todolist::findOrFail($id);
        $todolist->status = 4;
        $todolist->save();

        return response()->json([
            'type' => 'success'
        ]);
    }

    public function permit_submission_list()
    {
        $permits = PermitSubmission::latest()->get();
        return view('admin.permit.index', compact('permits'));
    }

    public function set_approved_permit($id)
    {
        $permit = PermitSubmission::findOrFail($id);
        $permit->status = 3;
        $permit->save();

        return redirect()->back()->with('success', 'Approved Permit Submission is Successfully');
    }

    public function set_not_approved_permit($id)
    {
        $permit = PermitSubmission::findOrFail($id);
        $permit->status = 2;
        $permit->save();

        return redirect()->back()->with('success', 'Not Approved Permit Submission is Successfully');
    }

    public function delete_permit($id)
    {
        $permit = PermitSubmission::findOrFail($id);
        $permit->delete();

        return redirect()->back()->with('success', 'Deleted Permit Submission is Successfully');
    }

    public function kpi_view()
    {
        $todolists = $this->kpi_todolist();
        $precenses = $this->kpi_precense();
        $achiev = $this->kpi_target_achiev();
        $initiative = $this->kpi_initiative();
        $avg = $this->chartDataKpi();

        $employes = Employee::latest()->get()->map(function($employe) use ($todolists, $precenses, $achiev, $initiative){
            $todolist = collect($todolists)->where('employe_id', $employe->id)->first()['nilaiAkhir'] ?? 0;
            $precens = collect($precenses)->where('employe_id', $employe->id)->first()['nilaiAkhir'] ?? 0;
            $initiativ = collect($initiative)->where('employe_id', $employe->id)->first()['score'] ?? 0;
            $achiev = $achiev->find($employe->id)?->target_achiev ?? 0;
            $finalScore = $todolist + $precens + $achiev + $initiativ;

            $employe->final_score = $finalScore;
            $employe->color = $this->getColorPercentage($finalScore);
            return $employe;
        })->sortByDesc('final_score')->values();
        
        $targetData = [
            'client' => $this->formatedData(Todolist::whereMonth('created_at', now()->month)->where('type', 1)->count(), get_static_option('target_client', 0)),
            'design' => $this->formatedData(Todolist::whereMonth('created_at', now()->month)->where('type', 2)->count(), get_static_option('target_design', 0)),
            'content' => $this->formatedData(Todolist::whereMonth('created_at', now()->month)->where('type', 3)->count(), get_static_option('target_content', 0)),
            'closing' => $this->formatedData(Todolist::whereMonth('created_at', now()->month)->where('type', 4)->count(), get_static_option('target_closing', 0))
        ];

        return view('admin.kpi.index', compact('todolists', 'precenses', 'achiev', 'employes', 'targetData', 'initiative', 'avg'));
    }

    public function kpiAllEmploye()
    {
        $todolists = $this->kpi_todolist();
        $precenses = $this->kpi_precense();
        $achiev = $this->kpi_target_achiev();
        $initiative = $this->kpi_initiative();

        $employes = Employee::latest()->get()->map(function($employe) use ($todolists, $precenses, $achiev, $initiative){
            $todolist = collect($todolists)->where('employe_id', $employe->id)->first()['nilaiAkhir'] ?? 0;
            $precens = collect($precenses)->where('employe_id', $employe->id)->first()['nilaiAkhir'] ?? 0;
            $initiativ = collect($initiative)->where('employe_id', $employe->id)->first()['score'] ?? 0;
            $achiev = $achiev->find($employe->id)?->target_achiev ?? 0;
            $finalScore = $todolist + $precens + $achiev + $initiativ;

            $employe->final_score = $finalScore;
            $employe->color = $this->getColorPercentage($finalScore);
            $employe->todolist = $todolist;
            $employe->achiev = $achiev;
            $employe->precense = $precens;
            $employe->initiativ = $initiativ;
            return $employe;
        })->sortByDesc('final_score')->values()->toArray();

        return $employes;
    }

    private function chartDataKpi()
    {
        $data = KeyPerformance::latest()
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy(function($item){
                return $item->created_at->format('Y-m');
            })
            ->map(function($item){
                return [
                    'count' => $item->count(),
                    'avg' => $item->avg('final_score'),
                    'month' => $item->first()->created_at->format('M')
                ];
            });

        $months = collect();
        $year = now()->year;

        for($month = 1; $month <= 12; $month++){
            $date = Carbon::create($year, $month, 1);
            $monthKey = $date->format('Y-m');
            $monthName = $date->format('M');

            $months->put($monthKey, [
                'count' => 0,
                'avg' => 0,
                'month' => $monthName
            ]);
        }

        $result = $months->map(function($monthData, $key) use ($data){
            if($data->has($key)){
                return $data->get($key);
            }

            return $monthData;
        })->values();

        return $result;
    }

    private function formatedData($value, $target)
    {
        $percentage = $value > 0 && $target > 0 ? ($value / $target) * 100 : 0;
        $percentage = round($percentage, 2);
        return [
            'value' => $value.'/'.$target,
            'percentage' => $percentage,
            'color' => $this->getColorPercentage($percentage)
        ];
    }

    public function kpi_settings(Request $request)
    {
        $validate = $request->validate([
            'target_client' => 'required',
            'target_content' => 'required',
            'target_design' => 'required',
            'target_closing' => 'required'
        ]);

        foreach($validate as $key => $item){
            update_static_option($key, $item);
        }

        return redirect()->back()->with('success', 'Saving KPI settings successfully');
    }

    private function getTotalDays()
    {
        $dayStart = Carbon::now()->startOfMonth();
        $dayEnd = Carbon::now()->endOfMonth();

        $daysInMonth = collect(CarbonPeriod::create($dayStart, $dayEnd))
            ->filter(function ($date){
                return !$date->isSunday();
            })->count();

        return $daysInMonth;
    }

    private function getColorPercentage($percentage)
    {
        switch (true) {
            case $percentage >= 80:
                $color = 'success';
                break;
            case $percentage >= 50:
                $color = 'info';
                break;
            case $percentage >= 25:
                $color = 'warning';
                break;
            case $percentage < 25 || $percentage == 0;
                $color = 'danger';
                break;
        }

        return $color ? $color : 'danger';
    }

    private function kpi_todolist()
    {
        $daysInMonth = $this->getTotalDays();

        $task = Todolist::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy('employee_id')
            ->map(function($task,$employe_id) use ($daysInMonth){
                $totalNilai = 0;

                $perhari = $task->groupBy(function($item){
                    return Carbon::parse($item->created_at)->format('Y-m-d');
                });

                collect($perhari)->map(function($item, $tanggal) use (&$totalNilai){
                    $totalTask = $item->count();
                    $totalSelesai = $item->where('status', 4)->count();
                    $nilai = 0;

                    if($totalTask == 0){
                        $nilai = 0;
                    } else if($totalSelesai == 0){
                        $nilai = 20;
                    } else {
                        $persen = ($totalSelesai / $totalTask) * 100;

                        if($persen < 50){
                            $nilai = 40;
                        } else if( $persen <= 80){
                            $nilai = 70;
                        } else {
                            $nilai = 100;
                        }
                    }

                    $totalNilai += $nilai;
                });

                $nilaiAkhir = ($totalNilai / $daysInMonth) / 100 * 30;
                $percentage = round(($nilaiAkhir / 30) * 100, 0);

                $totalTask = $task->count();
                $totalDone = $task->where('status', 4)->count();

                return [
                    'employe_id' => $employe_id,
                    'totalTask' => $totalTask,
                    'totalDone' => $totalDone,
                    'nilaiAkhir' => round($nilaiAkhir, 2),
                    'percentage' => $percentage,
                    'color' => $this->getColorPercentage($percentage),
                    'totalDays' => $daysInMonth
                ];
            })->values();
        
        return Employee::latest()->get()->map(function($employe) use ($task, $daysInMonth){
            $task = collect($task)->where('employe_id', $employe->id)->first();
            if($task){
                return [
                    'employe_id' => $employe->id,
                    'name' => $employe->name,
                    'image' => get_data_image($employe->image)['img_url'],
                    ...$task
                ];
            }

            return [
                'employe_id' => $employe->id,
                'name' => $employe->name,
                'image' => get_data_image($employe->image)['img_url'],
                'totalTask' => 0,
                'totalDone' => 0,
                'nilaiAkhir' => 0,
                'percentage' => 0,
                'color' => $this->getColorPercentage(0),
                'totalDays' => $daysInMonth
            ];
        })->sortByDesc('percentage')->values();
    }

    private function kpi_precense()
    {
        $daysInMonth = $this->getTotalDays();
        
        $precense = Precense::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy('employe_id')
            ->map(function($precense, $employe_id) use ($daysInMonth){
                $totalPrecense = $precense->where('status', '!=', 3)->count();
                $totalOnTime = $precense->where('status', 1)->count();

                $nilaiPrecense = $totalPrecense > 0 ? ($totalPrecense / $daysInMonth) * (20 * 0.7) : 0;
                $nilaiOnTime = $totalOnTime > 0 ? ($totalOnTime / $daysInMonth) * (20 * 0.3) : 0;
                $nilaiAkhir = $nilaiPrecense + $nilaiOnTime;
                $percentage = $nilaiAkhir > 0 ? ($nilaiAkhir / 20) * 100 : 0;

                return [
                    'employe_id' => $employe_id,
                    'totalPrecense' => $totalPrecense,
                    'totalOnTime' => $totalOnTime,
                    'nilaiPrecense' => round($nilaiPrecense, 2),
                    'nilaiOnTime' => round($nilaiOnTime, 2),
                    'nilaiAkhir' => round($nilaiAkhir, 2),
                    'percentage' => round($percentage, 2),
                    'color' => $this->getColorPercentage($percentage),
                    'totalDays' => $daysInMonth 
                ];
            })->values();

        return Employee::latest()->get()->map(function($employe) use ($precense, $daysInMonth) {
            $precense = collect($precense)->where('employe_id', $employe->id)->first();

            if($precense){
                return [
                    'name' => $employe->name,
                    'image' => get_data_image($employe->image)['img_url'],
                    ...$precense
                ];
            }

            return [
                'employe_id' => $employe->id,
                'name' => $employe->name,
                'image' => get_data_image($employe->image)['img_url'],
                'totalPrecense' => 0,
                'totalOnTime' => 0,
                'nilaiPrecense' => 0,
                'nilaiOnTime' => 0,
                'nilaiAkhir' => 0,
                'percentage' => 0,
                'color' => $this->getColorPercentage(0),
                'totalDays' => $daysInMonth
            ];
        })->sortByDesc('percentage')->values();
    }

    private function kpi_target_achiev()
    {
        return Employee::latest()->get()->map(function($employe){
            $target = 0;
            $targetAchiev = 0;

            $target_content = get_static_option('target_content', 0);
            $target_design = get_static_option('target_design', 0);
            $target_client = get_static_option('target_client', 0);
            $target_closing = get_static_option('target_closing', 0);

            if($employe->position == 1){
                $target = $employe->todolist()->whereMonth('created_at', now()->month)->where('type', 3)->count();
                $targetAchiev = $target > 0 && $target_content > 0 ? ($target / $target_content) * 40 : 0;
            } else if($employe->position == 2){
                $target = $employe->todolist()->whereMonth('created_at', now()->month)->where('type', 2)->count();
                $targetAchiev = $target > 0 && $target_design > 0 ? ($target / $target_design) * 40 : 0;
            } else if($employe->position == 4){
                $target = $employe->todolist()->whereMonth('created_at', now()->month)->where('type', 1)->count();
                $targetAchiev = $target > 0 && $target_client > 0 ? ($target / $target_client) * 40 : 0;
            } else {
                $target = $employe->todolist()->whereMonth('created_at', now()->month)->where('type', 4)->count();
                $targetAchiev = $target > 0 && $target_closing > 0 ? ($target / $target_closing) * 40 : 0;
            }

            $percentage = $targetAchiev > 0 ? ($targetAchiev / 40) * 100 : 0; 
            
            $employe->total_achiev = $target;
            $employe->target_achiev = round($targetAchiev, 2);
            $employe->percentage = round($percentage, 2);
            $employe->color = $this->getColorPercentage($percentage);
            return $employe;
        })->sortByDesc('percentage')->values();
    }

    private function kpi_initiative()
    {
        return Employee::latest()->get()->map(function($employe){
            $todolist = Todolist::whereMonth('created_at', now()->month)->where('employee_id', '!=', $employe->id)->get();
            $totalTodo = $todolist->count();
            
            $totalComment = 0;
            $todolist->each(function($todo) use ($employe, &$totalComment){
                $comment = $todo->comments()->where('user_id', $employe->user->id)->count();
                if($comment >= 2){
                    $totalComment++;
                }
            });

            $score = $totalTodo > 0 && $totalComment > 0 ? $totalComment / $totalTodo * 10 : 0;
            $percentage = $score > 0 ? $score / 10 * 100 : 0;

            return [
                'employe_id' => $employe->id,
                'name' => $employe->name,
                'image' => get_data_image($employe->image)['img_url'],
                'total_comment' => $totalComment,
                'total_todo' => $todolist->count(),
                'score' => $score,
                'percentage' => $percentage,
                'color' => $this->getColorPercentage($percentage)
            ];
        })->sortByDesc('percentage')->values();
    }
}
