<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}
