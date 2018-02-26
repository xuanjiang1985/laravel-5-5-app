<?php

namespace App\Observers;

use App\User;

class UserObserver
{
	 /**
     * 监听用户创建事件.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * 监听用户创建/更新事件.
     *
     * @param  User  $user
     * @return void
     */
    public function saved(User $user)
    {
        $file = fopen(public_path()."/test.txt","a") or die("not found");
        fwrite($file, "更新了用户ID：".$user->id.'----'.$user->name."\n");
        fclose($file);
    }
}