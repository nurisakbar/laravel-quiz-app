<?php

namespace App\Http\Controllers\Admin;

use App\Models\Result;
use App\Models\Question;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\ResultRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use PDF;

class ResultController extends Controller
{
   
    public function index(Request $request): View
    {
        if($request->has('categori_id')){
            $results    = Result::where('category_id',$request->categori_id)->get();
            $category   = Category::where('id',$request->get('categori_id'))->first();
            return view('admin.results.result_by_category', compact('results','category'));
        }else{
            $categories = Category::where('categori_id',null)->get();
            return view('admin.results.category', compact('categories'));
        }
        
    }

    public function create(): View
    {
        $questions = Question::all()->pluck('question_text', 'id');

        return view('admin.results.create', compact('questions'));
    }

    public function store(ResultRequest $request): RedirectResponse
    {
        $result = Result::create($request->validated() + ['user_id' => auth()->id()]);
        $result->questions()->sync($request->input('questions', []));

        return redirect()->route('admin.results.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(Result $result,Request $request)
    {
        if($request->has('type'))
        {
            if($request->type == 'pdf')
            {
                $data['result'] = $result;
                $data['categories'] = Category::where('categori_id',$request->category)->get();
                $data['category'] = \App\Models\Category::findOrFail($_GET['category']);
                $pdf = PDF::loadView('admin.results.result_pdf', $data)->setPaper('A4', 'potrait');
                return $pdf->stream('laporan-'.$data['category']->name.'-'.$data['result']->user->name.'.pdf');
            }
        }
        return view('admin.results.show', compact('result'));
    }

    public function edit(Result $result): View
    {
        $questions = Question::all()->pluck('question_text', 'id');

        return view('admin.results.edit', compact('result', 'questions'));
    }

    public function update(ResultRequest $request, Result $result): RedirectResponse
    {
        $result->update($request->validated() + ['user_id' => auth()->id()]);
        $result->questions()->sync($request->input('questions', []));

        return redirect()->route('admin.results.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(Result $result): RedirectResponse
    {
        $result->delete();

        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    public function massDestroy()
    {
        Result::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
