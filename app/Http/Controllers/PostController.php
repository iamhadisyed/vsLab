<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/12/17
 * Time: 2:21 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Auth;
use Session;

class PostController extends Controller {

    public function __construct()
    {
        $this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    public function index()
    {
        $posts = Post::orderby('id', 'desc')->paginate(5);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        //Validation
        $this->validate($request, [
            'title'=>'required|max:100',
            'description' =>'required',
        ]);

        $title = $request['title'];
        $body = $request['description'];

        $post = Post::create($request->only('title', 'description'));

        //Display a successful message upon save
        return redirect()->route('posts.index')
            ->with('flash_message', 'Post,
             '. $post->title.' created');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view ('posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title'=>'required|max:100',
            'description'=>'required',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->input('title');
        $post->body = $request->input('description');
        $post->save();

        return redirect()->route('posts.show',
            $post->id)->with('flash_message',
            'Post, '. $post->title.' updated');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')
            ->with('flash_message',
                'Post successfully deleted');
    }
}