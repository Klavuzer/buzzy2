<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CommentRepository;

class CommentController extends Controller
{
    /**
     * @var \App\Repositories\CommentRepository
     */
    protected $commentRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $except = ['index', 'replies'];

        if (get_buzzy_config('COMMENTS_GUEST_COMMENT', false)) {
            $except = array_merge($except, ['store']);
        }
        if (get_buzzy_config('COMMENTS_GUEST_COMMENT_VOTING', false)) {
            $except = array_merge($except, ['vote']);
        }

        $this->middleware('DemoAdmin', ['except' => $except]);

        $this->commentRepository = $commentRepository;
    }

    public function index()
    {
        $data = [
            'post_id' => request()->get('post_id'),
            'user_id' => request()->get('user_id'),
            'sort' => request()->get('sort'),
        ];

        if (request()->expectsJson()) {
            return $this->ajax($data);
        }

        return $this->init($data);
    }

    /**
     * Init comment page
     *
     * @param array $args
     * @return void
     */
    public function init($args)
    {
        $popularComments = $this->commentRepository->getPopular($args);
        $comments = $this->commentRepository->get($args, $popularComments->pluck('id'));

        $json_data = [
            'requestData' => $args,
        ];

        $data =  compact(
            'popularComments',
            'comments',
            'json_data'
        );

        if ($args['user_id']) {
            return view('comments.pages.user_comments', $data);
        }

        return view('comments.pages.index', $data);
    }

    public function ajax($args)
    {
        $comments = $this->commentRepository->get($args);

        return response()->json(['status' => 'success', 'html' => view(
            'comments.pages._comments_list',
            compact(
                'comments'
            )
        )->render()]);
    }

    public function replies($id)
    {
        $comments = $this->commentRepository->getReplies($id);
        $moreExist = $comments->hasMorePages();
        $hideLinks = true;

        return response()->json(['status' => 'success', 'moreExist' => $moreExist, 'html' => view(
            'comments.pages._comments_list',
            compact(
                'comments',
                'hideLinks'
            )
        )->render()]);
    }

    public function vote(Request $request)
    {
        $this->validate(
            $request,
            [
                'comment_id' => 'required',
                'vote' => 'required',
            ]
        );

        $response = $this->commentRepository->vote(
            $request->only(
                [
                    'comment_id',
                    'vote',
                ]
            )
        );

        return $response->json();
    }

    /**
     * Store a comment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'comment' => 'required|between:1,1500',
            'post_id' => 'required|exists:posts,id',
            'user_email' => 'email',
        ];

        if (
            get_buzzy_config('BuzzyGuestCommentCaptcha') == 'on'
            && get_buzzy_config('reCaptchaKey') !== ''
            && !get_current_comment_user()->authenticated
        ) {
            $rules = array_merge($rules, [
                'g-recaptcha-response' => 'required|recaptcha'
            ]);
        }

        $this->validate($request, $rules);

        $commentData = $request->only([
            'comment',
            'parent_id',
            'spoiler',
            'type',
            'post_id',
        ]);

        $user_username = $request->input('user_username');
        $user_email = $request->input('user_email');

        if ($user_username && $user_email) {
            $commentData = array_merge($commentData, ["data" => [
                'guest' => true,
                'ipno' => $request->ip(),
                'username' => $request->input('user_username'),
                'email' => $request->input('user_email'),
            ]]);
        }

        $response = $this->commentRepository->store($commentData);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json([
            'comment' => $response->data(),
            'html' => view(
                'comments.pages._comment',
                [
                    'comment' => $response->data()
                ]
            )->render()
        ]);
    }

    /**
     * Update a comment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'comment' => 'required|between:1,1500',
        ];

        $this->validate($request, $rules,  [
            'required' => __('You need to write something!'),
        ]);

        $commentData = $request->only([
            'comment',
            'spoiler',
        ]);

        $response = $this->commentRepository->update($id, $commentData);

        if ($response->failed()) {
            return $response->json();
        }

        $comment = $this->commentRepository->show($id);

        return $response->json([
            'comment' => $comment,
            'html' => parse_comment_text($comment->comment)
        ]);
    }

    /**
     * Destroy
     *
     * @param  int $id
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->commentRepository->destroy($id);

        return $response->json();
    }

    /**
     * Show Report Form
     *
     * @param Illuminate\Http\Request $request
     * @param int $id comment id
     *
     * @return Illuminate\Http\Response
     */
    public function reportForm($id)
    {
        $response = $this->commentRepository->show($id);

        $comment = $response->data();

        return view('auth.pages.report', compact('comment'));
    }

    /**
     * Store Report
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id comment id
     *
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'body' => 'max:500',
            ]
        );

        $response = $this->commentRepository->report($id, [
            'body' => $request->input('body'),
        ]);

        return $response->json();
    }
}
