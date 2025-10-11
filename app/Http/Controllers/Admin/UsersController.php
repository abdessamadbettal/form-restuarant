<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get statistics
        $stats = [
            'total' => User::where('role', 'user')->count(),
            'today' => User::where('role', 'user')->whereDate('created_at', today())->count(),
            'this_week' => User::where('role', 'user')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => User::where('role', 'user')->whereMonth('created_at', now()->month)->count(),
        ];

        // Get unique countries
        $countries = User::where('role', 'user')
            ->whereNotNull('country')
            ->distinct()
            ->pluck('country')
            ->sort()
            ->values();

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'stats' => $stats,
            'countries' => $countries,
            'filters' => $request->only(['search', 'start_date', 'end_date', 'country']),
        ]);
    }

    public function export(Request $request)
    {
        $format = $request->format ?? 'xlsx';
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $country = $request->country;

        $filename = 'users_' . date('Y-m-d_His');

        switch ($format) {
            case 'xlsx':
                return Excel::download(new UsersExport($startDate, $endDate, $country), "{$filename}.xlsx");

            case 'csv':
                return Excel::download(new UsersExport($startDate, $endDate, $country), "{$filename}.csv");

            case 'json':
                return $this->exportJson($startDate, $endDate, $country, $filename);

            default:
                return back()->withErrors(['format' => 'Invalid export format']);
        }
    }

    protected function exportJson($startDate, $endDate, $country, $filename)
    {
        $query = User::where('role', 'user');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($country) {
            $query->where('country', $country);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'country' => $user->country,
                    'registered_date' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json($users)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.json\"");
    }
}
