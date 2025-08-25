<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function delete($id)
    {
        $a = Attachment::find($id);
        if($a == null) { return 'ok'; }
        $a->delete();

        return 'ok';
    }
}
