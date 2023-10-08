
@section('css')
    <style>
        @font-face {
            font-family: arabic-font;
            src: url('fonts/All-Genders-Regular-v4.otf');
        }
        .error {
            margin-top: 7px;
        }
        .error span {
            background: #4f0202;
            color: #fff;
        }
        .error span#desc {
            width: 80%;
            display: none;
            border-radius: 10px;
            font-family: arabic-font, serif;
            margin-top: -50px;
            margin-bottom: 15px;
            padding: 10px 10px;
            direction: rtl;
            background: #4f020278;
        }
    </style>

@endsection
<div id="login-model" class="uk-flex-top" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title uk-text-center">Log In</h2>
        </div>
        <div class="uk-modal-body">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->

            <div class="spinner loading dark-font" style="display:none;" >
                <div class="circle one"></div>
                <div class="circle two"></div>
                <div class="circle three"></div>
            </div>
            <br/>
            <br/>
            <form method="POST" >
                @csrf
                <div class="uk-margin uk-text-center">
                    <div class="error">
                        <span id="desc"></span>
                    </div>
                    <div class="uk-inline uk-width-3-4@m uk-width-1-1@s">
                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                        <input class="uk-input borderless uk-border-rounded" type="text" placeholder="ID or Email" name="login" id="login" :value="old('login')" required autofocus>

                    </div>
                    <div class="error">
                        <span id="login"></span>
                    </div>
                </div>

                <div class="uk-margin uk-text-center">
                    <div class="uk-inline uk-width-3-4@m uk-width-1-1@s">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input class="uk-input borderless uk-border-rounded password" type="password" placeholder="Password" name="password" id="password" required autocomplete="current-password">
                        <a class="uk-form-icon uk-form-icon-flip togglePassword"  uk-icon="icon: eye">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" cx="10" cy="10" r="3.45"></circle><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path></svg>
                        </a>

                    </div>
                    <div class="error">
                        <span id="password"></span>
                    </div>
                </div>
                <div class="uk-margin uk-text-center justify-center uk-flex">
                    <div class="uk-inline uk-width-3-4@m uk-width-1-1@s uk-flex space-in-between">
                        <label class="light-link"><input name="remember" class="uk-checkbox" type="checkbox" checked> Remember Me</label>

                        @if (Route::has('password.request'))
                            <label>
                                <a href="{{ route('password.request') }}" class="light-link"> Forget my password</a>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="recaptcha-wrapper">
                <!-- Google reCaptcha v2 -->
                    <!-- Google reCaptcha v2 -->
                    {!! htmlFormSnippet() !!}

                    <div class="error">
                        <span style="width: 200px; margin:auto;" id="g-recaptcha-response"></span>
                    </div>
                </div>
                <div class="uk-margin uk-text-center">
                    <div class="uk-inline uk-width-3-4@m uk-width-1-1@s">
                        <button class="uk-button uk-button-primary uk-width-1-1 border-radius" id="send">Sign In</button>
                    </div>
                </div>

                <div class="uk-margin uk-text-center">
                    <div class="uk-inline">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="light-link">Sign Up</a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@section('script')
    <script >

        $(document).ready(function (){
            $(document).on('click','#send',function (e){
                e.preventDefault();
                var login = $('#login').val();
                var password = $('#password').val();
                $.ajax({
                    url: "{{route('login')}}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}",
                    },
                    data: {login: login, password: password,'g-recaptcha-response':grecaptcha.getResponse()},
                    beforeSend: function() {
                        $('.loading').show();

                    },
                    success: function (res) {
                        if(res.status){
                            window.location.assign(`/u/${res.url}`)
                        }

                    },
                    error: function (res) {
                        var response = JSON.parse(res.responseText);

                        $.each(response.errors, function (key, val) {
                            $(".error #" + key).text(val[0]);
                        });
                        $('.loading').hide();
                        if(res.status=='401') {
                            $(".error #login" ).text("You have been logged in From another browser.");
                            $(".error #desc").text("ايها الطالب الجامد جمودة سيادتك ممكن تكون دخلت من نفس الجهاز بس من متفصح تاني كل طالب مسموح له بمتصفح واحد بس  😩 ومتفتحش اللينك من فيس بوك او من المتصفح الخفي المنصة مش هتعرفك لو حبيت تنورنا وتدخل تاني لأنك هتكون خفي 🤦‍♂️ افتح من جوجل كروم افضل😁 تواصل بأه مع الدعم عشان يجددولك الدخول😘").css('display','inline-block');
                        }
                        if(res.status=='422') {
                            if(response.errors['g-recaptcha-response']!==undefined){
                                $(".error #desc").text("Invalid Recaptcha");
                            }else{
                               $(".error #desc").text("يعز علينا انك مش عارف تدخل 🥺️ بس الصراحة كده البيانات اللي انت دخلتها غلط😢 حاول تتأكد من البيانات وتدخلها تاني بعد ما تعمل اعادة تحميل للصفحة🔃 لو واجهتك نفس المشكلة تواصل مع مكتب الدعم الفني هيساعدوك فورا").css('display','inline-block');
                            }
                        }
                }
                });
            })

        })
    </script>
@endsection
