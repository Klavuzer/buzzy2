@component('mail::message')
{{__('Hi :user_name, We have bad news!', ['user_name' => $comment->user->username])}}
<br>
{!!__('Your post :post_title has been deleted.', ['post_title' => '<b>'.$comment->post->title .'</b>'])!!}

{{__('Regards')}},<br>
{{ config('app.name') }}
@endcomponent
