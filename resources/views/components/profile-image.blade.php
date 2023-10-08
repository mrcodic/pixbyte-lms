@php
    $userId = Auth::user()->id;
    $userModel = App\Models\User::find($userId);
    $userData  = Auth::user()->type == 5 ? App\Models\ParentStudent::find($userId)->user : $userModel;
    $image='';

    if($userData->grade_id==1){
        $image=url('uploads/no-image/first-year.png');
    }elseif($userData->grade_id==2){
        $image=url('uploads/no-image/second-year.png');
    }else{
        $image=url('uploads/no-image/third-year.png');
    }
@endphp

{{--<a href="{{ route('dashboard')}}">--}}
    <img class="showImage" src="{{ (!empty($userData->profile_image))? url('uploads/profile_images/'. $userData->profile_image) : $image }}" alt="avatar">
{{--</a>--}}
