<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class PostController extends Controller
{

    public function showEditForm(Post $post)
    {
        return view("edit-post", compact("post"));
    }

    public function update(Post $post, Request $request)
    {
        $incomingFields = $request->validate([
            "title" => "required",
            "body" => "required"
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return redirect()->route("show.single.post", $post->id)->with('success', 'Post updated successfully');
    }

    public function delete(Post $post)
    {
        // here we used the policy the controler's way, it should have been cannot but it did not work so i replaced it with this solution
        // if (Gate::denies('delete', $post)) {
        //     return response('You cannot do that', 403);
        // }

        // here we count on using it in the middleware's way
        $post->delete();
        return redirect(route('show.profile', auth()->user()->username))->with('success', 'Post deleted successfully');
    }

    public function showCreateForm()
    {
        return view('create-post');
    }

    public function storeNewPost(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        //The strip_tags() function in PHP is used to remove HTML and PHP tags from a string to prevent security risks like XSS attacks
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        return redirect()->route('show.single.post', ['post'/*this var name has to be the same as in the route we'll need it in viewSinglePost*/ => $newPost->id])->with('success', 'You have created a new post');
    }

    public function viewSinglePost(Post $post)
    {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p></p><ul></ul><ol></ol><li></li><strong></strong><em></em><h1></h1><h2></h2><h3></h3>');
        return view('single-post', ['post' => $post]);
    }
}
