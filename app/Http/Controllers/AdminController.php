<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Precense;
use App\Models\Esp32Mode;
use Illuminate\View\View;
use App\Models\BackupData;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use App\Models\StaticOption;
use App\Models\TimePrecense;
use Illuminate\Http\Request;
use App\Events\PrecenseEvent;
use Illuminate\Validation\Rules;
use App\Events\RegisterCardEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

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
        
        // dd(Auth::user()->createToken('admin')->plainTextToken);

        return view('admin.employee.index', compact('employees', 'select_employ'));
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
}
