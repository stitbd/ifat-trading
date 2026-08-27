{{-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard</a>
        <div class="d-flex justify-content-center mt-3">
            @auth <!-- Check if the user is authenticated -->
                <p class="fs-5 text-white">{{ Auth::user()->name }}</p>
                <div class="ms-2">
                    <a href="{{ route('logout') }}" class="btn btn-sm btn-success"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endauth
            @guest <!-- Show login/register links if the user is not authenticated -->
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-secondary ms-2">Register</a>
            @endguest
        </div>
    </div>
</nav> --}}