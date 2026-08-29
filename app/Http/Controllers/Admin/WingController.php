<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Wing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WingController extends Controller
{
        public static function middleware(): array
    {
        return [
            'auth', // Require authentication for all actions
            new Middleware('permission:wing.view', only: ['index', 'getdata']),
            new Middleware('permission:wing.create', only: ['store']),
            new Middleware('permission:wing.edit', only: ['update']),
            new Middleware('permission:wing.delete', only: ['distroy']),
        ];
    }
    public function index()
    {
        return view('backend.wings.index');
    }

    /**
     * Get wing data for DataTables.
     */
    public function getdata(Request $request)
    {
        if ($request->ajax()) {

            $data = Wing::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)

                ->addColumn('image', function ($row) {

                    if ($row->image && file_exists(public_path('wings/image/' . $row->image))) {
                        return '<img src="' . asset('wings/image/' . $row->image) . '"
                                alt="Wing Image"
                                style="width:50px;height:50px;object-fit:cover;border-radius:5px;">';
                    }

                    return '<span class="text-muted">No Image</span>';
                })

                ->addColumn('action', function ($row) {

                    $editUrl = route('wing.edit', $row->id);
                    $deleteUrl = route('wing.destroy', $row->id);

                    $csrfToken = csrf_field();
                    $method = method_field('DELETE');

                  $editBtn = '<button data-id="' . $row->id . '" class="edit btn btn-sm btn-success me-2 rounded" style="padding:8px;"><span>' .
                        '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;">' .
                        '<g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862L21.1213 2.70705ZM18.2929 4.12126C18.6834 3.73074 19.3166 3.73074 19.7071 4.12126L19.8787 4.29283C20.2692 4.68336 20.2692 5.31653 19.8787 5.70705L18.8622 6.72357L17.3068 5.10738L18.2929 4.12126ZM15.8923 6.52185L17.4477 8.13804L10.4888 15.097L8.37437 15.6256L8.90296 13.5112L15.8923 6.52185ZM4 7.99994C4 7.44766 4.44772 6.99994 5 6.99994H10C10.5523 6.99994 11 6.55223 11 5.99994C11 5.44766 10.5523 4.99994 10 4.99994H5C3.34315 4.99994 2 6.34309 2 7.99994V18.9999C2 20.6568 3.34315 21.9999 5 21.9999H16C17.6569 21.9999 19 20.6568 19 18.9999V13.9999C19 13.4477 18.5523 12.9999 18 12.9999C17.4477 12.9999 17 13.4477 17 13.9999V18.9999C17 19.5522 16.5523 19.9999 16 19.9999H5C4.44772 19.9999 4 19.5522 4 18.9999V7.99994Z" fill="#ffffff"></path> </g></svg>' .
                        '</span></button>';

                    $deleteBtn = '<form action="' . $deleteUrl . '"
                                        method="POST"
                                        style="display:inline;">
                                    ' . $csrfToken . '
                                    ' . $method . '

                                    <button type="submit"
                                            class="delete btn btn-danger btn-sm"
                                            style="padding:8px;">
                                        <svg viewBox="0 0 24 24"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            style="width:20px;height:20px;">
                                            <path d="M6 7V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V7M6 7H5M6 7H8M18 7H19M18 7H16M10 11V16M14 11V16M8 7V5C8 3.89543 8.89543 3 10 3H14C15.1046 3 16 3.89543 16 5V7M8 7H16"
                                                stroke="#ffffff"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </form>';

                    return '<div class="d-flex align-items-center mb-2">'
                        . $editBtn
                        . $deleteBtn
                        . '</div>';
                })

                ->rawColumns(['image', 'action'])
                ->make(true);
        }
    }

   public function show(string $id)
{
    $data = Wing::findOrFail($id);

    return view('backend.wings.show', compact('data'));
}
    public function create()
    {
        return view('backend.wings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'imported_number' => 'nullable|string|max:100|unique:wings,imported_number',
            'bin_number' => 'nullable|string|max:50|unique:wings,bin_number',
            'mobile_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'image' => 'nullable|file|image|max:2048',
            'authority_signature' => 'nullable|file|image|max:2048',
            'description' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $imagePath = null;
            $signaturePath = null;

            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_' . uniqid() . '.' . $extension;

                $path = 'wings/image/';

                $file->move(public_path($path), $filename);

                $imagePath = $filename;
            }

         
            if ($request->hasFile('authority_signature')) {

                $file = $request->file('authority_signature');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_signature_' . uniqid() . '.' . $extension;

                $path = 'signature/';

                $file->move(public_path($path), $filename);

                $signaturePath = $filename;
            }


            Wing::create([
                'name' => $request->name,
                'imported_number' => $request->imported_number,
                'bin_number' => $request->bin_number,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'image' => $imagePath,
                'authority_signature' => $signaturePath,
                'description' => $request->description,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wing Created Successfully!',
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }


    public function edit(string $id)
    {
        $data = Wing::findOrFail($id);

        return view('backend.wings.edit', compact('data'));
    }


    public function update(Request $request, string $id)
    {
        $find = Wing::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:100',

            'imported_number' => [
                'nullable',
                'string',
                'max:100',
                'unique:wings,imported_number,' . $id,
            ],

            'bin_number' => [
                'nullable',
                'string',
                'max:50',
                'unique:wings,bin_number,' . $id,
            ],

            'mobile_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'image' => 'nullable|file|image|max:2048',
            'authority_signature' => 'nullable|file|image|max:2048',
            'description' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'imported_number' => $request->imported_number,
                'bin_number' => $request->bin_number,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'description' => $request->description,
                'updated_by' => Auth::id(),
            ];

        

            if ($request->hasFile('image')) {

                if ($find->image !== null) {

                    $imagePath = public_path('wings/image/' . $find->image);

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $file = $request->file('image');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_' . uniqid() . '.' . $extension;

                $path = 'wings/image/';

                $file->move(public_path($path), $filename);

                $data['image'] = $filename;
            }

        

            if ($request->hasFile('authority_signature')) {

                if ($find->authority_signature !== null) {

                    $signaturePath = public_path(
                        'signature/' . $find->authority_signature
                    );

                    if (file_exists($signaturePath)) {
                        unlink($signaturePath);
                    }
                }

                $file = $request->file('authority_signature');

                $extension = $file->getClientOriginalExtension();

                $filename = time() . '_signature_' . uniqid() . '.' . $extension;

                $path = 'signature/';

                $file->move(public_path($path), $filename);

                $data['authority_signature'] = $filename;
            }

            $find->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wing Updated Successfully!',
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = Wing::findOrFail($id);

       

        if ($find->image !== null) {

            $imagePath = public_path('wings/image/' . $find->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


        if ($find->authority_signature !== null) {

            $signaturePath = public_path(
                'signature/' . $find->authority_signature
            );

            if (file_exists($signaturePath)) {
                unlink($signaturePath);
            }
        }



        $find->update([
            'updated_by' => Auth::id(),
        ]);

        $find->delete();

        Alert::success('Success', 'Wing deleted successfully!');

        return redirect()->route('wing.index');
    }
}
