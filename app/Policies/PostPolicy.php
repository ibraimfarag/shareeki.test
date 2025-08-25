public function update(User $user, Post $post)
{
    return $user->id === $post->user_id;
}
