{{-- Desktop version --}}
<ul class="uk-navbar-nav hidden-small">
    <li class="{{ Request::is('instructors*') ? 'uk-active': ''}}">
        <a href="" class="">Instructors</a><label class="soon-text nav">Soon</label>
    </li>
    <li class="">
        <a href="{{ route('get-classrank-classes')}}" class="">Student ranks</a>
    </li>
    <li class="">
        <a href="{{ route('store.index')}}" class="">Mives store</a>
    </li>

</ul>

{{-- Mobile version --}}
<ul class="uk-navbar-nav mobile-nav hidden-large">
    <li class="">
        <a href="" class="">Instructors</a><label class="soon-text nav">Soon</label>
    </li>
    <li class="">
        <a href="{{ route('get-classrank-classes')}}" class="">Student ranks</a>
    </li>
    <li class="">
        <a href="{{ route('store.index')}}" class="">Mives store</a>
    </li>

</ul>
