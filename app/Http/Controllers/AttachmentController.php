<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Support\Facades\File; 
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function delete($id)
    {
        $a = Attachment::find($id);
        if($a == null) { return 'null'; }

        $the_post_title = Post::findOrFail($a->post_id)->title;
        $attachment_name = $a->name;

        $image = asset("storage/main/attachments/${the_post_title}/${attachment_name}");

        File::delete($image);

        $a->delete();

        return 'ok';
    }
}
