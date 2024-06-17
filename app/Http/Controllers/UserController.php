<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

// use Intervention\Image\Laravel\Facades\Image;

class UserController extends Controller
{
    public function showAvatarForm()
    {
        return view("avatar-form");
    }

    public function storeAvatar(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'avatar' => 'required|image|max:3000|mimes:jpeg,png,jpg,gif', // validate image file type
        ]);

        // Process the uploaded image
        $filename = $this->processAvatar($request->file('avatar'));

        // Update the user's avatar in the database
        $user = Auth::user(); // Retrieve the currently authenticated user
        $oldAvatar = $user->avatar;
        $user->avatar = $filename;
        $user->save(); // Save the user record
        // dd($user->avatar);
        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return redirect()->route('show.profile', $user->username)->with('success', 'You have uploaded the avatar successfully');
    }

    public function processAvatar($image)
    {
        $filename = auth()->user()->id . "-" . uniqid() . '.jpg';
        $destinationPath = storage_path('app/public/avatars/');

        // Ensure the storage directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Check the MIME type and create the image from the uploaded file accordingly
        $mime = $image->getMimeType();
        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($image);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($image);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($image);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        // Get image dimensions
        list($width, $height) = getimagesize($image);

        // Create a new true color image
        $newImage = imagecreatetruecolor(120, 120);

        // Resize the image
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, 120, 120, $width, $height);

        // Save the image
        imagejpeg($newImage, $destinationPath . $filename, 90);

        // Free up memory
        imagedestroy($newImage);
        imagedestroy($sourceImage);

        return $filename;
    }

    public function showHomepage()
    {
        return view('homepage-fade');
    }

    public function showLoginForm()
    {
        return view('homepage');
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required',
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            return redirect()->route('home')->with('success', 'You have successfully logged in, enjoy');
        } else {
            return redirect()->route('home')->with('failure', 'Invalid login, try again');
        }
    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect()->route('home')->with('success', 'Thank you for creating an account, explore');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('home')->with('success', 'You are now logged out, see you again');
    }

    public function profile(User $user)
    {
        return view('profile-posts', [
            'username' => $user->username,
            'posts' => $user->posts()->latest()->get(),
            'postCount' => $user->posts()->count(),
            'avatar' => $user->avatar
        ]);
    }
}
