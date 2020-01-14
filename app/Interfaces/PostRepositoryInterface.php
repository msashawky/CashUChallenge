<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PostRepositoryInterface
{
    public function getUserFollowers($user);
    public function getUserFollowings(Request $request);
    public function validatePostData(Request $request);
    public function createPost(Request $request);
    public function validateAddComment(Request $request);
    public function createPostComment(Request $request);
    public function validateReportComment(Request $request);
    public function createReportComment(Request $request);
    public function validateUpdateComment(Request $request);
    public function updatePostComment(Request $request);
    public function deletePostComment(Request $request,$id);
    public function deletePost(Request $request, $id);
    public function validateUpdatePostData(Request $request);
    public function updatePost(Request $request);
    public function getMyFollowingPosts($user);
    public function toggleFollowingUser(Request $request);
    public function getUserFollowingsIds(Request $request);


}
