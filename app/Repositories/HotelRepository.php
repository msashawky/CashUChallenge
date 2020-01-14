<?php

namespace App\Repositories;
use App\Traits\ApiResponseTrait;
use App\Interfaces\HotelRepositoryInterface;


//HotelRepository Class is implementing HotelRepositoryInterface Methods

class HotelRepository implements HotelRepositoryInterface
{
    use ApiResponseTrait; //Trait for Handling Our API Responses

    protected $user;
    protected $post;
    protected $postVideo;
    protected $postImage;
    protected $postComments;
    protected $commentsReports;
    protected $userFollowings;
    protected $postLikes;
    protected $userFollowers;

    public function __construct(User $user, Post $post, PostVideo $postVideo, PostImage $postImage,
                                PostComments $postComments, PostCommentsReports $commentsReports,
                                UserFollowings $userFollowings, PostLikes $postLikes, UserFollowers $userFollowers)
    {
        $this->user = $user;
        $this->post = $post;
        $this->postVideo = $postVideo;
        $this->postImage = $postImage;
        $this->postComments = $postComments;
        $this->commentsReports = $commentsReports;
        $this->userFollowings = $userFollowings;
        $this->postLikes = $postLikes;
        $this->userFollowers = $userFollowers;
    }

    public function search()
    {
        // TODO: Implement search() method.
    }

    public function getUserFollowers($user)
    {
        return $user->myFollowers;

    }

    public function getUserFollowings(Request $request)
    {
        return decodeUser($request->bearerToken())->myFolloweings;

    }

    public function getUserFollowingsIds(Request $request){

        return decodeUser($request->bearerToken())->myFolloweings->pluck('following_id')->toArray();
    }

    public function toggleFollowingUser(Request $request)
    {
        $user = decodeUser($request->bearerToken());
        $request['user_id'] = $user->id;

        $isFollowing = $this->userFollowings->where([['user_id', '=', $user->id], ['following_id', '=', $request->following_id]])->first();

        if ($isFollowing) {
            //remove row from user followers first
            $this->userFollowers->where([['user_id', '=', $request->following_id], ['follower_id', '=', $request->user_id]])->first()->delete();

            return $isFollowing->delete();

        } else {
            $followingUser = $this->userFollowings->create($request->all());

            if ($followingUser) {

                $data = ['user_id' => $request->following_id, 'follower_id' => $request->user_id];
                return $this->userFollowers->create($data);
            }
        }
        return false;

    }

    public function createPost(Request $request)
    {
        $user = decodeUser($request->bearerToken());

        $request['owner_id'] = $user->id;

        if ($request->type == 'text' && filled($request->description)) {

            return $this->post->create($request->all());
        } else if ($request->type == 'video' && filled($request->link)) {
            $post = $this->post->create($request->all());

            $this->postVideo->create(array(
                'post_id' => $post->id,
                'link' => $request->link));

            return $post;
        } else if ($request->type == 'photo' && filled($request->photo)) {

            $post = $this->post->create($request->all());

            $this->postImage->create(array(
                'post_id' => $post->id,
                'photo' => $request->photo));

            return $post;
        }


        return false;

    }

    public function updatePost(Request $request)
    {
        $user = decodeUser($request->bearerToken());

        $post = $this->post->where([['owner_id', '=', $user->id], ['id', '=', $request->post_id]])
            ->first();

        $updatedPost = $post->update($request->all());

        if ($post->type == 'text' && $updatedPost) {
            return true;
        } else if ($post->type == 'video') {

            return $post->video->update($request->all());

        } else if ($post->type == 'photo') {

            return $post->photo->update($request->all());
        }

        return false;

    }

    public function deletePost(Request $request, $id)
    {
        $user = decodeUser($request->bearerToken());

        return $this->post->where([['owner_id', '=', $user->id], ['id', '=', $id]])
            ->first()->delete();
    }


    public function toggleLikePost(Request $request)
    {
        $user = decodeUser($request->bearerToken());

        $isLiked = $this->postLikes->where([['user_id', '=', $user->id], ['post_id', '=', $request->post_id]])->first();

        if ($isLiked) {
            return $isLiked->delete();
        } else {
            $request['user_id'] = $user->id;
            return $this->postLikes->create($request->all());
        }
    }


    public function createPostComment(Request $request)
    {
        $user = decodeUser($request->bearerToken());

        $request['user_id'] = $user->id;

        return $this->postComments->create($request->all());

    }


    public function updatePostComment(Request $request)
    {
        $user = decodeUser($request->bearerToken());

        $comment = $this->postComments->where([['user_id', '=', $user->id], ['post_id', '=', $request->post_id],
            ['id', '=', $request->comment_id]])->first();
        return $comment->update($request->all());

    }


    public function deletePostComment(Request $request, $id)
    {
        $user = decodeUser($request->bearerToken());

        return $this->postComments->where([['user_id', '=', $user->id], ['id', '=', $id]])
            ->first()->delete();

    }

    public function createReportComment(Request $request)
    {
        $user = decodeUser($request->bearerToken());

        $request['reporter_id'] = $user->id;

        // if comment_id is not exists will return 500 error from db
        //so i didn't check for id to increase query performance
        return $this->commentsReports->create($request->all());

    }

    public function getMyFollowingPosts($user)
    {

        // $user = decodeUser($request->bearerToken());

        // get the ids only for following id in an array
        $userFollowings = $this->userFollowings->where('user_id', $user->id)->select('following_id')->get()->toArray();

        // get all posts for following id users in array order by data
        return $this->post->whereIn('owner_id', $userFollowings)->orderBy('created_at', 'DESC')->get();

    }


    public function validatePostData(Request $request)
    {
        return $this->apiValidation($request, [
            'type' => 'required|in:video,photo,text',
        ]);
    }

    public function validateUpdatePostData(Request $request)
    {
        return $this->apiValidation($request, [
            'post_id' => 'required|numeric'
        ]);
    }


    public function validateAddComment(Request $request)
    {
        return $this->apiValidation($request, [
            'post_id' => 'required|numeric',
            'comment' => 'required',
        ]);
    }

    public function validateUpdateComment(Request $request)
    {
        return $this->apiValidation($request, [
            'comment_id' => 'required|numeric',
            'post_id' => 'required|numeric',
            'comment' => 'required',
        ]);
    }


    public function validateReportComment(Request $request)
    {
        return $this->apiValidation($request, [
            'comment_id' => 'required|numeric',
        ]);
    }


    public function validateFollowingId(Request $request)
    {
        return $this->apiValidation($request, [
            'following_id' => 'required|numeric',
        ]);
    }


}
