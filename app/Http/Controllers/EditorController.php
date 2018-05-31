<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class EditorController extends Controller
{
    /**
     * EditorController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::all();
        return view('documents', compact('documents'));
    }

    public function all(Request $request)
    {
        $query = Document::query();
        if ($request->has('author')) {
            $query->where('author', 'like', '%' . $request->get('author') . '%');
        }
        if ($request->filled('from') && $request->filled('to') && $request->filled('date_type')) {
            $query->whereBetween($request->get('date_type'), [$request->get('from'), $request->get('from')]);
        }
        return response()->json($query->get()->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = route('documents.store');
        $record = [];
        return view('document', compact('action', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentRequest $request)
    {
        $doc = new Document();
        $doc->title = $request->get('title');
        $doc->content = $request->get('content');
        $doc->author = Auth::user()->name;
        $doc->tags = $request->get('tags');
        $doc->save();
        return Redirect::to(route('documents.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $action = route('documents.update', ['id' => $id]);
        $record = Document::find($id);
        return view('document', compact('record', 'action'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentRequest $request, $id)
    {
        $doc = Document::find($id);

        $doc->title = $request->get('title');
        $doc->content = $request->get('content');
        $doc->author = Auth::user()->name;
        $doc->tags = $request->get('tags');
        $doc->save();

        return Redirect::to(route('documents.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = Document::find($id);
        $doc->delete();
        return response()->json("", Response::HTTP_NO_CONTENT);
    }


    public function getFile($id)
    {
        $doc = Document::find($id);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(Markdown::convertToHtml($doc->content));
        return $pdf->stream();
    }
}
