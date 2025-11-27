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
                    <input type="search" name="q" class="form-control" placeholder="Search games..." aria-label="Search" required minlength="2">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="login-button ms-2">
        </div>
    </div>
</nav>
