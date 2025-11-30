<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <span class="navbar-brand">
            <a href="{{ route('home') }}"><div class="logo">PlayScore</div></a>
        </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
            </ul>
            <form class="d-flex" id="searchForm" role="search">
                <div class="input-group">
                    <input type="search" name="q" class="form-control" placeholder="{{ __('messages.search_games') }}" aria-label="Search" required minlength="2">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="login-button ms-2">
        </div>
        <div class="language-switcher ms-2">
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-globe"></i> {{ strtoupper(app()->getLocale()) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">🇺🇸 English</a></li>
                    <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">🇸🇦 العربية</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
