<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\FunPrompt;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class FunPromptController extends Controller
{
    public function index(Request $request, string $type): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = FunPrompt::where('type', $type)->latest();

            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('title', 'LIKE', "%$searchTerm%");
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= " checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label"></label></div>';
                    return $status;
                })
                ->addColumn('action', function ($data) use ($type) {
                    return '<div class="btn-group btn-group-sm" role="group">
                                <a href="' . route('fun-prompts.edit', [$type, $data->id]) . '" class="text-white btn btn-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="text-white btn btn-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make();
        }

        return view('backend.layouts.fun_prompts.index', compact('type'));
    }

    public function create(string $type): View|RedirectResponse
    {
        if (User::find(auth()->user()->id)) {
            return view('backend.layouts.fun_prompts.create', compact('type'));
        }
        return redirect()->route('fun-prompts.index', $type);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'  => 'required|string',
                'status' => 'required|string|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            FunPrompt::create([
                'title'  => $request->title,
                'status' => $request->status,
                'type'   => $type,
            ]);

            return redirect()->route('fun-prompts.index', $type)->with('t-success', 'Fun Prompt created successfully.');
        } catch (Exception) {
            return redirect()->route('fun-prompts.index', $type)->with('t-error', 'Failed to create Fun Prompt.');
        }
    }

    public function edit(string $type, int $id): View
    {
        $data = FunPrompt::where('type', $type)->findOrFail($id);
        return view('backend.layouts.fun_prompts.edit', compact('data', 'type'));
    }

    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'  => 'required|string',
                'status' => 'required|string|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = FunPrompt::where('type', $type)->findOrFail($id);
            $data->update([
                'title'  => $request->title,
                'status' => $request->status,
            ]);

            return redirect()->route('fun-prompts.index', $type)->with('t-success', 'Fun Prompt updated successfully.');
        } catch (Exception) {
            return redirect()->route('fun-prompts.index', $type)->with('t-error', 'Update failed.');
        }
    }

    public function status(string $type, int $id): JsonResponse
    {
        $data = FunPrompt::where('type', $type)->findOrFail($id);
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();

        return response()->json([
            'success' => $data->status === 'active',
            'message' => $data->status === 'active' ? 'Published successfully.' : 'Unpublished successfully.',
            'data'    => $data,
        ]);
    }

    public function destroy(string $type, int $id): JsonResponse
    {
        $data = FunPrompt::where('type', $type)->findOrFail($id);
        $data->delete();

        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
