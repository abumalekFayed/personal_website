<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Notifications\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        return Email::latest()->get();
    }

    public function store()
    {
        $email = Email::create(request()->all());
        $email->notify(new Reply(['body' => 'تم استقبال رسالتك سيتم الرد عليك في اقرب وقت']));
        // Mail::send('announcement', ['name' => $email->name, 'msg' => $email->body], function ($message) use ($email) {
        //     $message->to($email->email)->subject('Announcement email');
        //     $message->from('azucdentazhar@gmail.com', 'AZUCDENT');
        // });
    }

    public function update(Email $email)
    {
        $email->update(['reply' => request()->body, 'state' => 1]);
        $email->notify(new Reply(['body' => ['body' => request()->body]]));
        return 'ok';
    }
}
