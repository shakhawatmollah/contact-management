<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteRequest;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class WebsiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:view websites'])->only(['index', 'show', 'data']);
        $this->middleware(['permission:create websites'])->only(['create', 'store']);
        $this->middleware(['permission:edit websites'])->only(['edit', 'update', 'regenerateApiKeys']);
        $this->middleware(['permission:delete websites'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.websites.index');
    }

    /**
     * Process datatable ajax request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getWebsites(Request $request)
    {
        $query = Website::query()->select('websites.*');

        // Filter by access if not super admin
        if (!auth()->user()->hasRole('super-admin')) {
            $userWebsiteIds = auth()->user()->websites()->pluck('websites.id');
            $query->whereIn('id', $userWebsiteIds);
        }

        return DataTables::of($query)
            ->addColumn('contacts_count', function ($website) {
                return $website->contacts()->count();
            })
            ->addColumn('actions', function ($website) {
                $viewBtn = auth()->user()->can('view websites')
                    ? '<a href="' . route('admin.websites.show', $website->id) . '" class="btn btn-sm btn-info">View</a>'
                    : '';

                $editBtn = auth()->user()->can('edit websites')
                    ? '<a href="' . route('admin.websites.edit', $website->id) . '" class="btn btn-sm btn-primary">Edit</a>'
                    : '';

                $deleteBtn = auth()->user()->can('delete websites')
                    ? '<form method="POST" action="' . route('admin.websites.destroy', $website->id) . '" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                      </form>'
                    : '';

                return $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn;
            })
            ->editColumn('is_active', function ($website) {
                return $website->is_active ? 'Active' : 'Inactive';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.websites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WebsiteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebsiteRequest $request)
    {
        // Create website
        $website = Website::create([
            'name' => $request->name,
            'url' => $request->url,
            'api_key' => Str::random(32),
            'secret_key' => Str::random(64),
            'is_active' => $request->has('is_active'),
            'contact_email' => $request->contact_email,
            'description' => $request->description,
        ]);

        // Assign website to current user if not super-admin
        if (!auth()->user()->hasRole('super-admin')) {
            $website->users()->attach(auth()->id());
        }

        // Add additional users if specified
        if ($request->filled('user_ids')) {
            $website->users()->syncWithoutDetaching($request->user_ids);
        }

        return redirect()->route('admin.websites.index')
            ->with('success', 'Website created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $website = Website::with('users')->findOrFail($id);

        // Check if user has access to this website
        if (!auth()->user()->hasRole('super-admin') &&
            !auth()->user()->websites->contains($website->id)) {
            abort(403, 'Unauthorized action.');
        }

        // Get recent contacts
        $recentContacts = $website->contacts()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.websites.show', compact('website', 'recentContacts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $website = Website::with('users')->findOrFail($id);

        // Check if user has access to this website
        if (!auth()->user()->hasRole('super-admin') &&
            !auth()->user()->websites->contains($website->id)) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.websites.edit', compact('website'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WebsiteRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WebsiteRequest $request, $id)
    {
        $website = Website::findOrFail($id);

        // Check if user has access to this website
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->websites->contains($website->id)) {
            abort(403, 'Unauthorized action.');
        }

        // Update website details
        $website->name = $request->name;
        $website->url = $request->url;
        $website->is_active = $request->has('is_active');
        $website->contact_email = $request->contact_email;
        $website->description = $request->description;

        $website->save();

        // Update user assignments if super-admin
        if (auth()->user()->hasRole('super-admin') && $request->filled('user_ids')) {
            $website->users()->sync($request->user_ids);
        }

        return redirect()->route('admin.websites.index')
            ->with('success', 'Website updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $website = Website::findOrFail($id);

        // Check if user has access to this website
        if (!auth()->user()->hasRole('super-admin') &&
            !auth()->user()->websites->contains($website->id)) {
            abort(403, 'Unauthorized action.');
        }

        // Delete website
        $website->delete();

        return redirect()->route('admin.websites.index')
            ->with('success', 'Website deleted successfully.');
    }

    /**
     * Regenerate API keys for the website
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function regenerateApiKeys($id)
    {
        $website = Website::findOrFail($id);

        // Check if user has access to this website
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->websites->contains($website->id)) {
            abort(403, 'Unauthorized action.');
        }

        // Regenerate API keys
        $website->api_key = Str::random(32);
        $website->secret_key = Str::random(64);
        $website->save();

        return redirect()->route('admin.websites.edit', $website->id)
            ->with('success', 'API keys regenerated successfully.');
    }
}
