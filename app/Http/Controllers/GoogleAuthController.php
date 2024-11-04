<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Yajra\DataTables\Facades\DataTables;

class GoogleAuthController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('dashboard');
    }


    // ------------ DATATABLE FETCH CODE ---------------
    public function getuser()
    {
        return DataTables::of(User::query())->make(true);
    }
    //---- END CODE --


    // --------------------------------- ALL FILTERING CODE --------------------

    public function filteruser(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'sdate' => 'nullable|date',
            'edate' => 'nullable|date|after:sdate',
        ]);


        $choice = $request->choice;
        if ($choice == 'd') {
            $today = User::whereDate('created_at', Carbon::today())->get();
            return DataTables::of($today)->make(true);
        }
        if ($choice == 'w') {
            $weekly = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
            return DataTables::of($weekly)->make(true);
        }
        if ($choice == 'm') {
            $monthly = User::whereMonth('created_at', Carbon::now()->month)->get();
            return DataTables::of($monthly)->make(true);
        }
        if ($choice == 'y') {
            $yearly = User::whereYear('created_at', Carbon::now()->year)->get();
            return DataTables::of($yearly)->make(true);
        }
        if ($request->filled('sdate') && $request->filled('edate')) {
            $sdate = $request->input('sdate');
            $edate = Carbon::parse($request->input('edate'))->endOfDay();
            $filtered = User::whereBetween('created_at', [$sdate, $edate])->get();
            return DataTables::of($filtered)->make(true);
        }
    }

    //-----  End of CODE ----

    public function loginpage()
    {
        return view('welcome');
    }

    public function AddUser()
    {
        return view('user');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->input('password')),
        ]);

        if ($user) {
            return redirect()->route('login')->with('success', 'User registered successfully!');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            return redirect()->route('home')->with('success', 'Logged in successfully!');
        }
        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }



    //login with google code *****

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function CallbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $user = User::where('google_id', $google_user->getId())->orWhere('email', $google_user->getEmail())->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $google_user->getId();
                    $user->save();
                }
                Auth::login($user);
                return redirect()->route('home');
            } else {
                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId()
                ]);
                Auth::login($new_user);
                return redirect()->route('home');
            }
        } catch (\Throwable $th) {
            dd('something went wrong' . $th->getMessage());
        }
    }

    //*********************** END CODE  */

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    //  ******** download csv file code here ***********
    public function downloadFilteredFile(Request $request)
    {

        $choice = $request->input('choice');
        $query = User::query();

        if ($choice == 'd') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($choice == 'w') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($choice == 'm') {
            $query->whereMonth('created_at', Carbon::now()->month);
        } elseif ($choice == 'y') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->filled('sdate') && $request->filled('edate')) {
            $sdate = $request->input('sdate');
            $edate = Carbon::parse($request->input('edate'))->endOfDay();
            $query->whereBetween('created_at', [$sdate, $edate]);
        }

        $data = $query->get();
        $filename = 'filtered_users.csv';
        $file = fopen($filename, 'w+');
        fputcsv($file, ['Name', 'Email', 'Created At', 'Updated At']);

        foreach ($data as $user) {
            fputcsv($file, [$user->name, $user->email, $user->created_at, $user->updated_at]);
        }

        fclose($file);
        return response()->download($filename);
    }
    // ******** END OF DOWNLOAD CSV FILE CODE  ***********
}
