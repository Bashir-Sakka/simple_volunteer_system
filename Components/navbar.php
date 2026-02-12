<?php
function getUrlWithLang($newLang)
{
    // 1. Get all current URL parameters (e.g., ['page' => 2, 'id' => 5])
    $params = $_GET;

    // 2. Update or add the language parameter
    $params['lang'] = $newLang;

    // 3. Rebuild the query string: page=2&id=5&lang=en
    return $_SERVER['PHP_SELF'] . '?' . http_build_query($params);
}
function genNav()
{
    echo '
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">

        <!-- Logo + Brand -->
        <a class="navbar-brand d-flex align-items-center" href="' . BASE_URL . '/">
            <img src="LOGO.jpg" alt="Logo" width="40" height="40" class="me-2 rounded-circle shadow-sm">
            <span class="fw-bold fs-5 text-white">
        </a>

        <!-- Right-side buttons -->
        <div class="d-flex gap-2">
            <!-- Settings -->
            <a href="' . BASE_URL . '/settings" class="d-flex align-items-center">
                <button type="button" class="btn text-white btn-sm ">
                    <i class="bi bi-gear fs-5 me-1"></i> 
                </button>
            </a>
    
        

            <!-- Language switch -->
            <a href="' . getUrlWithLang('en') . '" class="d-flex align-items-center">
<button type="button" class="btn d-flex text-white btn-sm fs-5">EN</button>
</a>
<a href="' . getUrlWithLang('ar') . '" class="d-flex align-items-center">
    <button type="button" class="btn text-white d-flex btn-sm fs-5">AR</button>
</a>
</div>
</div>
</nav>

';
}

?>