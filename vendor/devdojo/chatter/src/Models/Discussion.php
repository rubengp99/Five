<?php

namespace DevDojo\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use SoftDeletes;
    
    protected $table = 'chatter_discussion';
    public $timestamps = true;
    protected $fillable = ['title', 'chatter_category_id', 'user_id', 'slug', 'color','name','picture'];
    protected $dates = ['deleted_at', 'last_reply_at'];

    public function user()
    {
        return $this->belongsTo(config('chatter.user.namespace'));
    }

    public function category()
    {
        return $this->belongsTo(Models::className(Category::class), 'chatter_category_id');
    }

    public function posts()
    {
        return $this->hasMany(Models::className(Post::class), 'chatter_discussion_id');
    }

    public function post()
    {
        return $this->hasMany(Models::className(Post::class), 'chatter_discussion_id')->orderBy('created_at', 'ASC');
    }

    public function last($id)
    {
        return \DB::table('chatter_discussion')->where('chatter_category_id',$id)->latest('created_at')->first();
    }

    public function created_at($id)
    {
        return \DB::table('chatter_discussion')->select('created_at')->where('user_id',$id)->first();
    }

    public function username($id)
    {
        return \DB::table('users')->select('name')->where('id',$id)->get();
    }

    public function picture($id)
    {
        return \DB::table('users')->select('profile_image')->where('id',$id)->get();
    }

    public function postsCount()
    {
        return $this->posts()
        ->selectRaw('chatter_discussion_id, count(*)-1 as total')
        ->groupBy('chatter_discussion_id');
    }

    public function findSlug($id){
        return \DB::table('chatter_categories')->select('slug')->where('id',$id)->get();
    }

    public function users()
    {
        return $this->belongsToMany(config('chatter.user.namespace'), 'chatter_user_discussion', 'discussion_id', 'user_id');
    }
}
